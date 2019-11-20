<?php

namespace Backend\Modules\Imscevents\Ajax;

use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Base\AjaxAction as BackendBaseAjaxAction;
use Backend\Core\Language\Language as BL;
use Backend\Modules\Imscevents\Engine\Model as BackendImsceventsModel;
use Common\Core\Model as CommonModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class IctImport Импорт данных из шлюза ICT
 */
class IctImport extends BackendBaseAjaxAction
{
    /** @var int Количество загруженных событий, через которое происходит фиксация статуса */
    const BATCH_SIZE = 10;

    /** @var int Порядковый номер текущей картинки. */
    private $currentImageIndex = 0;

    /** @var array Список картинок для мероприятий. */
    private $eventImages = [];

    public function execute(): void
    {
        parent::execute();

        session_write_close();
        set_time_limit(0);

        $ictCommands = BackendImsceventsModel::getDnppCommands('ICT');
        $loadedEvents = BackendImsceventsModel::getLoadedIctIds();

        $session = [
            'user_id'           => BackendAuthentication::getUser()->getUserId(),
            'total_step_count'  => count($ictCommands),
            'current_step'      => 0,
            'total_news_count'  => 0,
            'current_news'      => 0
        ];
        BackendImsceventsModel::updateDnppSessionStatus($session);

        // Перебираем полученные шлюзы */
        foreach ($ictCommands as $command) {
            $session['current_step'] = $session['current_step'] + 1;
            $session['current_news'] = 0;
            $session['total_news_count'] = 0;
            BackendImsceventsModel::updateDnppSessionStatus($session);

            $imageFolder = rtrim(FRONTEND_FILES_PATH, DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR
                . trim($command['image_folder'], DIRECTORY_SEPARATOR);
            $this->eventImages = is_dir($imageFolder) ? $this->getImageList($imageFolder) : [];

            $events = $this->getDataViaCurl($command['command'], $command['password']);
            if (isset($events['status']) ? intval($events['status']) : 200 != 200) {
                $this->output($events['status'], null, 'Ошибка ДИТ: ' . $events['title']);
                return;
            }

            $session['total_news_count'] = count($events);
            BackendImsceventsModel::updateDnppSessionStatus($session);

            // Перебираем и сохраняем все полученные события
            foreach ($events as $event) {
                $session['current_news'] = $session['current_news'] + 1;

                if (!isset($loadedEvents[$event['id']])) {
                    $this->addIctEvent($event);
                } elseif ($event['updated'] > $loadedEvents[$event['id']]) {
                    $backup = BackendImsceventsModel::getIctEvent($event['id']);
                    BackendImsceventsModel::deleteIctEvent($event['id']);
                    $id = $this->addIctEvent($event);
                    BackendImsceventsModel::updateEvent($id, [
                            'in_all' => $backup['in_all'],
                            'active' => $backup['active'],
                            'main' => $backup['main'],
                            'hidden' => $backup['hidden'],
                            'lead' => $backup['lead']
                        ]
                    );
                }

                if ($session['current_news'] % self::BATCH_SIZE == 0) {
                    BackendImsceventsModel::updateDnppSessionStatus($session);
                }
            }
        }

        BackendImsceventsModel::updateDnppSessionStatus($session);
        $this->output(Response::HTTP_OK);
    }

    /**
     * Выполняет добавление в базу данных события, полученного из шлюза.
     * @param array $event Полученные из шлюза сведения о событии.
     * @return int Идентификатор добавленного мероприятия.
     * @throws \SpoonDatabaseException
     */
    private function addIctEvent(array $event): int {
        $item = [
            'ict_id' => $event['id'],
            'dnpp_id' => 0,
            'title' => $event['title'],
            'short' => '',
            'description' => $event['description'] . "
              <br>
              <div class='ict-link'>
                <a href='https://ict.moscow/events/'>
                  <img class='ict-logo' src='/src/Frontend/Modules/Imscevents/Layout/Images/logo-ict.svg' width='80'/>
                  &nbsp;Источник ICT.Moscow
                </a>
              </div>    
            ",
            'address' => !empty($event['location']) ? $event['location'] : '',
            'site' => $event['slug'],

            'date'  => date('Y-m-d H:i:s', $event['dateRange']['start']),
            'enddate' => date('Y-m-d H:i:s', $event['dateRange']['finish']),

            'type' => $event['isPay'] ? '1001701' : '1001702',

            'in_all' => 0,
            'active' => 0,
            'main' => 0,
            'hidden' => 0,
            'lead' => '',
            'lang' => BL::getWorkingLanguage()
        ];

        if (isset($event['timeRange']) && (!empty($event['timeRange']['start']) || !empty($event['timeRange']['finish']))) {
            $item['time'] = !empty($event['timeRange']['start']) ? $event['timeRange']['start'] : '';
            if (!empty($event['timeRange']['finish'])) {
                if ($item['time'] != '') $item['time'] .= ' - ';
                $item['time'] .= $event['timeRange']['finish'];
            }
        } else {
            $item['time'] = $event['isAllDay'] ? 'Весь день' : '';
        }

        $id = BackendImsceventsModel::addEvent($item);

        $image = $this->getNextImage();
        if ($image) {
            BackendImsceventsModel::updateImage($id, $this->loadImage($id, $image));
        }

        return $id;
    }

    private function getDataViaCurl(string $url, string $password)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, trim($url));
        //curl_setopt($ch, CURLOPT_SSLVERSION,3);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type: application/json',
            'X-Accept-Project: ' . $password
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYSTATUS,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $fileObjects = curl_exec($ch);

        curl_close($ch);

        return json_decode($fileObjects, true);
    }

    /**
     * Возвращает ссылку на следуюущу картинку.
     * @return string
     */
    private function getNextImage(): ?string
    {
        if (count($this->eventImages) == 0) return null;

        $this->currentImageIndex++;
        if ($this->currentImageIndex == count($this->eventImages))
            $this->currentImageIndex = 0;

        return $this->eventImages[$this->currentImageIndex];
    }

    /**
     * Выполняет сканирование папки и формированеи списка файлов, найденных в ней.
     * @param string $folder Сканируемая папка.
     * @return array Список найденных файлов
     */
    private function getImageList(string $folder): array
    {
        try {
            $files = scandir($folder);
            $result = [];
            foreach ($files as $file) {
                $fullFileName = rtrim($folder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
                if (is_file($fullFileName))
                    $result[] = $fullFileName;
            }
            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function loadImage(int $id, string $sourceImage): string
    {
        $shortName = pathinfo($sourceImage)['basename'];
        $eventImagePath = FRONTEND_FILES_PATH . '/' . $this->getModule() . '/images/' . $id;
        $sourceImagePath = FRONTEND_FILES_PATH . '/' . $this->getModule() . '/images/ict/' . $id;

        BackendImsceventsModel::removeDir($eventImagePath);

        $filesystem = new Filesystem();
        $filesystem->mkdir([
            $eventImagePath,
            $sourceImagePath,
            $eventImagePath . '/128x128',
            $eventImagePath . '/512x'
        ]);

        copy($sourceImage, $sourceImagePath . '/' . $shortName);

        CommonModel::generateThumbnails($eventImagePath, $sourceImagePath . '/' . $shortName);

        return $sourceImagePath . '/' . $shortName;
    }
}
