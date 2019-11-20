<?php

namespace Backend\Modules\Imscevents\Ajax;

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAJAXAction;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Language\Language as BL;
use Common\Core\Model as CommonModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Backend\Modules\Imscevents\Engine\Model as BackendImsceventsModel;


/**
 * Class DnppImport Импорт событий из шлюза ДНПП
 * @package Backend\Modules\Imscevents\Ajax
 */
class DnppImport extends BackendBaseAJAXAction
{
    /** @var int Количество загруженных событий, через которое происходит фиксация статуса */
    const BATCH_SIZE = 10;

    public function execute(): void
    {
        parent::execute();

        // FIXME: Не использовать напрямую PHP функции управления сессиями
        session_write_close();

        set_time_limit(0);

        $dnppCommands = BackendImsceventsModel::getDnppCommands();

        $session = [
            'user_id'           => BackendAuthentication::getUser()->getUserId(),
            'total_step_count'  => count($dnppCommands),
            'current_step'      => 0,
            'total_news_count'  => 0,
            'current_news'      => 0
        ];
        BackendImsceventsModel::updateDnppSessionStatus($session);

        $loadedEvents = array_flip(BackendImsceventsModel::getLoadedDnppIds());

        // Перебираем все команды загрузки данных из шлюза ДНПП
        foreach ($dnppCommands as $command) {
            $session['current_step'] = $session['current_step'] + 1;
            $session['current_news'] = 0;
            $session['total_news_count'] = 0;
            BackendImsceventsModel::updateDnppSessionStatus($session);

            //$jsonEvents = file_get_contents($command['command']);
            //$events = json_decode($jsonEvents, true);
            $events = self::getDataViaCurl($command['command']);

            $session['total_news_count'] = count($events['data']);
            BackendImsceventsModel::updateDnppSessionStatus($session);
            // Перебираем и сохраняем все полученные события
            foreach ($events['data'] as $event) {
                $session['current_news'] = $session['current_news'] + 1;

                if (!isset($loadedEvents[$event['id']])) {
                    $this->addDnppEvent($event);
                    $command['last_dnpp_id'] = $event['id'];
                }

                if ($session['current_news'] % self::BATCH_SIZE == 0) {
                    BackendImsceventsModel::updateDnppCommand($command);
                    BackendImsceventsModel::updateDnppSessionStatus($session);
                }
            }
        }

        BackendImsceventsModel::updateDnppCommand($command);
        BackendImsceventsModel::updateDnppSessionStatus($session);

        BackendImsceventsModel::updateOiiData();

        $this->output(Response::HTTP_OK);
    }

    /**
     * Выполняет добавление в базу данных события, полученного из шлюза ДНПП.
     * @param array $event Полученные из шлюза сведения о событии.
     * @throws \SpoonDatabaseException
     */
    private function addDnppEvent(array $event) {
        $item = [
            'title' => $event['name'],
            'short' => $event['shortDescription'],
            'description' => $event['description'],
            'date'  => date('Y-m-d H:i:s', strtotime($event['beginDate'])),
            'enddate' => date('Y-m-d H:i:s', strtotime($event['endDate'])),
            'oii_id' => $event['innoObjectID'],
            'dnpp_id' => $event['id'],
            'site' => isset($event['webSite']) ? $event['webSite'] : '',
            'address' => $event['location'],
            'type' => $this->getType($event['baseDictionaries']),
            'in_all' => 0,
            'active' => 0,
            'main' => 0,
            'hidden' => 0,
            'lead' => '',
            'lang' => BL::getWorkingLanguage()
        ];

        $id = BackendImsceventsModel::addEvent($item);
        $image = $this->loadImage($id, $event);
        if ($image != '') {
            $item['image'] = $image;
            BackendImsceventsModel::updateEvent($id, $item);
        }
    }

    /**
     * Определяет используя переданные словари, является ли мероприятие платным, или нет.
     * @param array $dictionaries Словари
     * @return int Значение словаря, показывающее, является мероприятие платным
     */
    private function getType(array $dictionaries): int
    {
        foreach ($dictionaries as $dict) {
            if (in_array($dict['dictionaryID'], ['1001701', '1001702']))
                return $dict['dictionaryID'];
        }
        return 1001702;
    }

    /**
     * Выполняет загрузку картинки события из шлюза ДНПП.
     * @param int $id Идентификатор новости.
     * @param array $event Сведения о события, полученные из шлюза ДНПП.
     * @return string Имя файла с картикой.
     */
    private function loadImage(int $id, array $event): string
    {
        if (!$event['photos'])
            return '';

        $sourceImage = $event['photos'][0]['originalUrl'];
        $shortName = pathinfo($sourceImage)['basename'];
        $downloadedImagePath = FRONTEND_FILES_PATH . '/' . $this->getModule() . '/images/dnpp/' . $id;
        $eventImagePath = FRONTEND_FILES_PATH . '/' . $this->getModule() . '/images/' . $id;

        BackendImsceventsModel::removeDir($downloadedImagePath);
        BackendImsceventsModel::removeDir($eventImagePath);

        $filesystem = new Filesystem();
        $filesystem->mkdir([
            $downloadedImagePath,
            $eventImagePath . '/128x128',
            $eventImagePath . '/512x'
        ]);

        copy($sourceImage, $downloadedImagePath . '/' . $shortName);

        CommonModel::generateThumbnails($eventImagePath, $downloadedImagePath . '/' . $shortName);

        return $downloadedImagePath . '/' . $shortName;
    }

    private static function getDataViaCurl($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, trim($url));
        //curl_setopt($ch, CURLOPT_SSLVERSION,3);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json') );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYSTATUS,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $fileObjects = curl_exec($ch);

        curl_close($ch);

        return json_decode($fileObjects, true);
    }
}
