<?php

namespace Backend\Modules\Imscevents\Engine;

use Backend\Core\Language\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Modules\Imscoii\Engine\Model as BackendImscoiiModel;

class Model
{
    /** @var string SQL команда для формирования датагрида со списком команд ДНПП */
    const DNPP_COMMANDS_DATAGRID_QUERY = '
        SELECT  id, description, command, gate_id, image_folder, last_dnpp_id, password
        FROM    imscevents_dnpp_commands
        WHERE   `language` = ?
        ORDER BY
            id
    ';

    /*
     * Запрос datagrid для списка мероприятий
     */
    const EVENTS_DATAGRID_QUERY = 'SELECT id, title, date, enddate, active, oii_id FROM imscevents WHERE lang = "ru"';

    /*
 * Запрос datagrid для списка мероприятий
 */
    const EVENTS_DATAGRID_QUERY_EN = 'SELECT id, title, date, enddate, active, oii_id FROM imscevents WHERE lang = "en"';

    /*
     * Добавление мероприятия
     */
    public static function addEvent(array $item):int
    {
        $insertTime = BackendModel::getUTCDate(null, time());

        $item['dt_created'] = $insertTime;
        $item['dt_updated'] = $insertTime;

        if ($item['active'] == '1')
            $item['dt_activated'] = $insertTime;

        return BackendModel::getContainer()->get('database')->insert('imscevents', $item);
    }

    /*
     * Обновление мероприятия
     */
    public static function updateEvent(int $id, array $item):void
    {
        $item['dt_updated'] = BackendModel::getUTCDate(null, time());

        $item['inno_object_type_id'] = 0;
        $item['oii_name'] = '';
        if (isset($item['oii_id']) && $item['oii_id'] != '0') {
            $oii = BackendImscoiiModel::getObjectById($item['oii_id']);
            if (count($oii) > 0) {
                $item['inno_object_type_id'] = isset($oii['innoObjectTypeID']) ? $oii['innoObjectTypeID'] : 0;
                $item['oii_name'] = isset($oii['name']) ? $oii['name'] : '';
            }
        }

        $oldItem = self::getEvent($id);
        if ($oldItem['active'] == '0' && $item['active'] == '1')
            $item['dt_activated'] = $item['dt_updated'];

        BackendModel::getContainer()->get('database')->update(
            'imscevents',
            $item,
            'id = ?',
            [$id]
        );
    }

    /*
     * Проверка мероприятия на существование
     */
    public static function existsEvent(int $id):bool
    {
        return (bool) BackendModel::getContainer()->get('database')->getVar(
            'SELECT id
             FROM imscevents
             WHERE id = ?',
            [(int) $id]
        );
    }

    /*
     * Получаем мероприятие по id
     */
    public static function getEvent(int $id):array
    {
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT * FROM imscevents
             WHERE id = ?',
            [(int) $id]
        );
    }

    /*
     * Удаление мероприятия
     */
    public static function deleteEvent(int $id): void
    {
        BackendModel::getContainer()->get('database')->delete(
            'imscevents',
            'id = ?',
            [(int) $id]
        );
    }

    /*
     * Добавление/обновление изображения мероприятия
     */
    public static function updateImage(int $id, string $filename):void
    {
        BackendModel::getContainer()->get('database')->update(
            'imscevents',
            ['image' => $filename],
            'id = ?',
            [$id]
        );
    }

    /**
     * Выполняет получение списка команд для шлюза ДНПП
     * @param string $gate Идентификатор шлюза, из которого должна производиться загрузка.
     * @return array Набор команд для получения событий из шлюза ДНПП
     * @throws \SpoonDatabaseException
     */
    public static function getDnppCommands(string $gate = 'DNPP'): array
    {
        $database = BackendModel::getContainer()->get('database');
        return $database->getRecords(
            'SELECT id, command, COALESCE(last_dnpp_id, 0) last_dnpp_id, image_folder, password
                    FROM imscevents_dnpp_commands
                    WHERE `language` = ? AND `gate_id` = ?
                    ORDER BY id', [BL::getWorkingLanguage(), $gate]);
    }

    /**
     * Выполняет обновление в базе данных команды шлюза ДНПП.
     * @param array $command Сохраняемые данные.
     * @throws \SpoonDatabaseException
     */
    public static function updateDnppCommand(array $command): void
    {
        $database = BackendModel::getContainer()->get('database');
        $database->update('imscevents_dnpp_commands', $command, 'id = ?', $command['id']);
    }

    /**
     * Выполняет проверку, загружено ли событие ДНПП в базу данных.
     * @param int $dnpp_id ДНПП код события
     * @return bool <b>true</b> если событие с таким кодом загружено, <b>false</b> - в противном случае.
     * @throws \SpoonDatabaseException
     */
    public static function isDnppEventLoaded(int $dnpp_id): bool
    {
        $database = BackendModel::getContainer()->get('database');
        return (bool) $database->getRecord(
          'SELECT id FROM imscevents WHERE dnpp_id = ?', [$dnpp_id]
        );
    }

    /**
     * Возвращает из базы данных сведения о команде обмена со шлюзом ДНПП
     * @param int $id Идентификатор команды.
     * @return array Сведения о команде обмена со шлюзом ДНПП.
     * @throws \SpoonDatabaseException
     */
    public static function getDnppCommand(int $id): array
    {
        $database = BackendModel::getContainer()->get('database');
        return $database->getRecord('SELECT * FROM imscevents_dnpp_commands WHERE id = ?', [$id]);
    }

    /**
     * Выполняет удаление из базы данных ДНПП команды с указанным идентификатором.
     * @param int $id Идентификатор удаляемой ДНПП команды.
     * @throws \SpoonDatabaseException
     */
    public static function deleteDnppCommand(int $id): void
    {
        $database = BackendModel::getContainer()->get('database');
        $database->delete('imscevents_dnpp_commands', 'id = ?', [$id]);
    }

    /**
     * Выполняет добавление новой команды ДНПП шлюза.
     * @param array $command Сведения о добавляемой команде.
     * @return int Идентификатор добавленной записи.
     * @throws \SpoonDatabaseException
     */
    public static function addDnppCommand(array $command): int
    {
        $database = BackendModel::getContainer()->get('database');
        return $database->insert('imscevents_dnpp_commands', $command);
    }

    /**
     * Выполняет удаление указанной папки вместе с содержимым.
     * @param $dir Удаляемая папка
     */
    public static function removeDir($dir): void
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);

            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        self::removeDir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }

    /**
     * Выполняет сохранение в таблице текущего статуса обновления мероприятий из ДНПП шлюза.
     * @param array $status Сведения о текущем статусе обновления.
     * @throws \SpoonDatabaseException
     */
    public static function updateDnppSessionStatus(array $status): void
    {
        $database = BackendModel::getContainer()->get('database');
        if ((bool) $database->getRecord(
            'SELECT user_id FROM imscevents_dnpp_update_session WHERE user_id = ?',
            [$status['user_id']])) {
            $database->update('imscevents_dnpp_update_session', $status, 'user_id = ?', $status['user_id']);
        } else {
            $database->insert('imscevents_dnpp_update_session', $status);
        }
    }

    /**
     * Получает из базы данных текущий статус обновления данных из ДНПП шлюза.
     * @return array Сведения о текущем статусе обновления мероприятий из шлюза.
     * @throws \SpoonDatabaseException
     */
    public static function getDnppSessionStatus(): array
    {
        $database = BackendModel::getContainer()->get('database');
        return (array) $database->getRecord('SELECT * FROM imscevents_dnpp_update_session WHERE user_id = ?',
            [BackendAuthentication::getUser()->getUserId()]);
    }

    /**
     * Получает список последних созданных мероприятий.
     * @param int $ndays Количество дней, за которые получаем мероприятия.
     * @return array Выбранные мероприятия
     * @throws \SpoonDatabaseException
     */
    public static function getLatestEvents(int $ndays): array
    {
        $database = BackendModel::getContainer()->get('database');
        $news = (array)$database->getRecords(
            'SELECT * 
                    FROM imscevents
                    WHERE active = 1 AND lang = ? AND dt_activated >= TIMESTAMPADD(DAY, -?, CURRENT_TIMESTAMP) 
                    ORDER BY date DESC
                    LIMIT 20',
            [BL::getWorkingLanguage(), $ndays]
        );

        foreach ($news as &$item) {
            $item['image'] = pathinfo($item['image'], PATHINFO_BASENAME);
            $item['date'] = self::getEventDate($item['date']);
        }

        return (array) $news;
    }

    /**
     * Выполняет удаление мероприятий, загруженных из ДНПП, и обнуление счетчика последней загруженной новости.
     * @throws \SpoonDatabaseException
     */
    public static function deleteDnppEvents(): void
    {
        $database = BackendModel::getContainer()->get('database');

        $dnppEvents = (array)$database->getRecords('SELECT id FROM imscevents WHERE COALESCE(dnpp_id, 0) <> 0');
        foreach ($dnppEvents as $event) {
            $downloadedImagePath = FRONTEND_FILES_PATH . '/Imscevents/images/dnpp/' . $event['id'];
            $eventImagePath = FRONTEND_FILES_PATH . '/Imscevents/images/' . $event['id'];

            self::removeDir($downloadedImagePath);
            self::removeDir($eventImagePath);
        }

        $database->delete('imscevents', 'COALESCE(dnpp_id, 0) <> 0');
        $database->update('imscevents_dnpp_commands', ['last_dnpp_id' => 0]);
    }

    /**
     * Выполняет удаление мероприятий, загруженных из ICT
     * @throws \SpoonDatabaseException
     */
    public static function deleteIctEvents(): void
    {
        $database = BackendModel::getContainer()->get('database');

        $ictEvents = (array)$database->getRecords("SELECT id FROM imscevents WHERE COALESCE(ict_id, '') <> ''");
        foreach ($ictEvents as $event) {
            $eventImagePath = FRONTEND_FILES_PATH . '/Imscevents/images/' . $event['id'];
            $sourceImagePath = FRONTEND_FILES_PATH . '/Imscevents/images/ict/' . $event['id'];
            self::removeDir($eventImagePath);
            self::removeDir($sourceImagePath);
        }

        $database->delete('imscevents', "COALESCE(ict_id, '') <> ''");
    }

    /**
     * Возвращает событие, загруженное из ICT
     * @param string $ictId ICT идентификатор события.
     * @return array Данные события.
     * @throws \SpoonDatabaseException
     */
    public function getIctEvent(string $ictId): array {
        $database = BackendModel::getContainer()->get('database');
        return (array) $database->getRecord("
            SELECT  *
            FROM    imscevents 
            WHERE   ict_id = ?
            LIMIT 1", [$ictId]);
    }


    /**
     * Выполняет удаление мероприятия, загруженного из ICT.
     * @param string $ictId ICT идентификатор удаляемого мероприятия.
     * @throws \SpoonDatabaseException
     */
    public static function deleteIctEvent(string $ictId) {
        $database = BackendModel::getContainer()->get('database');

        $event = (array) $database->getRecord("
            SELECT  id 
            FROM    imscevents 
            WHERE   ict_id = ?
            LIMIT 1", [$ictId]);

        $eventImagePath = FRONTEND_FILES_PATH . '/Imscevents/images/' . $event['id'];
        $sourceImagePath = FRONTEND_FILES_PATH . '/Imscevents/images/ict/' . $event['id'];

        self::removeDir($eventImagePath);
        self::removeDir($sourceImagePath);
        self::deleteEvent($event['id']);
    }

    /**
     * Выполняет обновление в справочнике мероприятий сведений об объектах инновационной структуры.
     */
    public static function updateOiiData(): void
    {
        $database = BackendModel::getContainer()->get('database');
        $newsList = (array) $database->getRecords(
            'SELECT id, oii_id, lang 
                     FROM imscevents
                    WHERE COALESCE(oii_id, 0) <> 0 AND COALESCE(inno_object_type_id, 0) = 0');

        foreach ($newsList as $news) {
            $oii = BackendImscoiiModel::getObjectById($news['oii_id'], $news['lang']);
            if (count($oii) > 0) {
                $item = [
                    'id' => $news['id'],
                    'inno_object_type_id' => isset($oii['innoObjectTypeID']) ? $oii['innoObjectTypeID'] : 0,
                    'oii_name' => isset($oii['name']) ? $oii['name'] : ''
                ];
                $database->update('imscevents', $item, 'id = ?', [$news['id']]);
            }
        }
    }

    public static function getEventDate($begindate, $enddate = 0, $showYear = 0, $language = 'ru'){
        if($language == 'ru') {
            $monthes = array("","января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
        } elseif(LANGUAGE == 'en') {
            $monthes = array("","january", "february", "march", "april", "may", "june", "july", "august",
                "september", "october", "november", "december");
        }

        $beginTimeStamp = strtotime($begindate);
        $beginMonth = $monthes[date("n", $beginTimeStamp)];
        $beginDay = date("j", $beginTimeStamp);
        $beginYear = date("Y", $beginTimeStamp);
        $endTimeStamp = strtotime($enddate);
        $endMonth = $monthes[date("n", $endTimeStamp)];
        $endDay = date("j", $endTimeStamp);
        $endYear = date("Y", $endTimeStamp);
        if(!$enddate && !$showYear) {
            return $beginDay . " " . $beginMonth;
        }elseif(!$enddate && $showYear){
            return $beginDay." ".$beginMonth." ".$beginYear;
        }elseif($beginMonth == $endMonth && $beginDay == $endDay && !$showYear){
            return $beginDay." ".$beginMonth;
        }elseif($beginMonth == $endMonth && $beginDay == $endDay && $showYear){
            return $beginDay." ".$beginMonth." ".$beginYear;
        }elseif($beginMonth == $endMonth && $beginDay != $endDay && !$showYear){
            return $beginDay." - ".$endDay." ".$beginMonth;
        }elseif($beginMonth == $endMonth && $beginDay != $endDay && $showYear){
            return $beginDay." - ".$endDay." ".$beginMonth." ".$beginYear;
        }elseif($showYear){
            return $beginDay." ".$beginMonth." ".$beginYear." - ".$endDay." ".$endMonth." ".$endYear;
        }else{
            return $beginDay." ".$beginMonth." - ".$endDay." ".$endMonth;
        }
    }

    /**
     * Получает спискок загруженных из ДНПП событий.
     * @return array Идентификаторы загруженных событий.
     * @throws \SpoonDatabaseException
     */
    public static function getLoadedDnppIds(): array
    {
        $database = BackendModel::getContainer()->get('database');
        return (array) $database->getColumn('
            SELECT dnpp_id FROM imscevents WHERE dnpp_id IS NOT NULL GROUP BY dnpp_id ORDER BY dnpp_id'
        );
    }

    /**
     * Получает спискок загруженных из ДИТ событий.
     * @return array Идентификаторы загруженных событий.
     * @throws \SpoonDatabaseException
     */
    public static function getLoadedIctIds(): array
    {
        $database = BackendModel::getContainer()->get('database');
        $events = (array) $database->getRecords('
            SELECT  ict_id, MAX(UNIX_TIMESTAMP(dt_created)) dt_updated 
            FROM    imscevents 
            WHERE   ict_id IS NOT NULL 
            GROUP BY 
                ict_id 
            ORDER BY ict_id'
        );
        $result = [];
        foreach ($events as $event) {
            $result[$event['ict_id']] = $event['dt_updated'];
        }
        return $result;
    }
}
