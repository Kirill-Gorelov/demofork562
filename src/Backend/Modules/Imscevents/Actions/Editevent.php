<?php

namespace Backend\Modules\Imscevents\Actions;

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Symfony\Component\Filesystem\Filesystem;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Modules\Imscevents\Engine\Model as BackendImsceventsModel;
use Backend\Modules\Imscoii\Engine\Model as BackendImscoiiModel;
use Backend\Modules\Imscevents\Form\ImsceventsDelType;
use Backend\Core\Language\Language as BL;

/*
 * Контроллер редактирования мероприятия
 */
class Editevent extends BackendBaseActionEdit {
    /** @var array Типы объектов */
    private $innoObjectTypes;

    public static function getUTCDate(string $format = null, int $timestamp = null): string
    {
        $format = ($format !== null) ? (string) $format : 'Y-m-d H:i:s';
        if ($timestamp === null) {
            return date($format);
        }

        return date($format, $timestamp);
    }


    public static function getUTCTimestamp(\SpoonFormDate $date, \SpoonFormTime $time = null): int
    {
        // validate date/time object
        if (!$date->isValid() || ($time !== null && !$time->isValid())
        ) {
            throw new \Exception('You need to provide two objects that actually contain valid data.');
        }

        $year = date('Y', $date->getTimestamp());
        $month = date('m', $date->getTimestamp());
        $day = date('j', $date->getTimestamp());
        $hour = 0;
        $minute = 0;

        if ($time !== null) {
            // define hour & minute
            list($hour, $minute) = explode(':', $time->getValue());
        }

        // make and return timestamp
        return mktime($hour, $minute, 0, $month, $day, $year);
    }

    public function execute(): void
    {

        /*
         * Получаем id редактируемого мероприятия
         */
        $this->id = $this->getRequest()->query->getInt('id');

        /*
         * Проверка на наличие мероприятия в базе
         */
        if ($this->id !== 0 && BackendImsceventsModel::existsEvent($this->id)) {

            parent::execute();

            /*
             * Получение данных мероприятия
             */
            $this->record = BackendImsceventsModel::getEvent($this->id);

            $this->loadForm();

            $this->validateForm();
            $this->loadDeleteForm();

            $this->parse();
            $this->display();
        } else {
            /*
             * Если нету - то редирект на список мероприятий
             */
            $this->redirect(BackendModel::createUrlForAction('Index'));
        }

    }

    /*
     * Тип мероприятия (платно/бесплатно)
     */
    private $types = [
        1001701 => 'Платно',
        1001702 => 'Бесплатно'
    ];

    /*
    * Создаём форму
    */
    private function loadForm(): void
    {
        $this->form = new BackendForm('edit');

        $this->form->addText('title', $this->record['title'], 512, 'form-control title', 'form-control danger title');

        $this->form->addEditor('short', $this->record['short'], 'form-control short', 'form-control danger short');

        $this->form->addText('lead', $this->record['lead'], 512, 'form-control lead', 'form-control danger lead');

        $this->form->addText('time', $this->record['time'], 255, 'form-control time', 'form-control danger time');

        $this->form->addText('address', $this->record['address'], 255, 'form-control address', 'form-control danger address');

        $this->form->addText('site', $this->record['site'], 255, 'form-control site', 'form-control danger site');

        $this->form->addDropdown('type', $this->types, $this->record['type'], false, 'form-control type',
            'form-control danger type');

        $this->form->addEditor('description', $this->record['description'], 'form-control description', 'form-control danger title');

        $this->form->addText('image', $this->record['image'], null, 'form-control image', 'form-control danger image');
        $this->form->addCheckbox('delete_image');

        $this->form->addText('dnpp_id', $this->record['dnpp_id'], 255, 'form-control dnpp_id', 'form-control danger dnpp_id');

        $this->form->addText('ict_id', $this->record['ict_id'], 255, 'form-control ict_id', 'form-control danger ict_id');

        $this->form->addDate('date', strtotime($this->record['date']), strtotime("NOW"),null, 'form-control date', 'form-control danger date');

        $this->form->addDate('enddate', strtotime($this->record['enddate']), strtotime("NOW"),null, 'form-control date', 'form-control danger date');

        $this->innoObjectTypes = BackendImscoiiModel::getInnoObjectTypesFilter(BL::getWorkingLanguage());
        $this->form->addDropdown('innoObjectTypeId', $this->innoObjectTypes, $this->record['inno_object_type_id']);

        $this->form->addText('oii_id', $this->record['oii_id'], 255, 'form-control oii_id', 'form-control danger oii_id');

        $this->form->addText('oii_name', $this->record['oii_name'], 255, 'form-control oii_name', 'form-control danger oii_name');

        $this->form->addCheckbox('active', $this->record['active']);

        $this->form->addCheckbox('in_all', $this->record['in_all']);

        $this->form->addCheckbox('main', $this->record['main']);

        $this->form->addCheckbox('made_in_moscow', $this->record['made_in_moscow']);

        $this->form->addCheckbox('hidden', $this->record['hidden']);
    }

    /*
     * Метод удаления дерикторий с файлами
     */
    private static function removeDir($dir): void
    {
        if (is_dir($dir))
        {
            $objects = scandir($dir);

            foreach ($objects as $object)
            {
                if ($object != "." && $object != "..")
                {
                    if (filetype($dir . "/" . $object) == "dir")
                    {
                        self::removeDir($dir . "/" . $object);
                    }
                    else
                    {
                        unlink($dir . "/" . $object);
                    }
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }

    /*
     * Проверяем данные с формы на валидность
     */
    private function validateForm(): void
    {
        if($this->form->isSubmitted())
        {
            $this->form->cleanupFields();

            /*
             * Проверяем на валидность и заполнение
             */
            $this->form->getField('title')->isFilled('Заполните поле');
            $this->form->getField('date')->isValid('Введите корректную дату');

            /*
             * Если данные с формы корректны - сохраняем
             */
            if($this->form->isCorrect())
            {
                $oii_id = intval($this->form->getField('oii_id')->getValue());
                $companyInfo = BackendImscoiiModel::getObjectById($oii_id, BL::getWorkingLanguage());

                $item = [
                    'title' => $this->form->getField('title')->getValue(),
                    'short' => $this->form->getField('short')->getValue(),
                    'description' => $this->form->getField('description')->getValue(),
                    'date' => self::getUTCDate(
                        null,
                        self::getUTCTimestamp(
                            $this->form->getField('date')
                        )
                    ),
                    'enddate' => self::getUTCDate(
                        null,
                        self::getUTCTimestamp(
                            $this->form->getField('enddate')
                        )
                    ),
                    'inno_object_type_id' => isset($companyInfo['innoObjectTypeId']) ? $companyInfo['innoObjectTypeId'] : 0,
                    'oii_id' => $oii_id,
                    'oii_name' => isset($companyInfo['name']) ? $companyInfo['name'] : '',
                    'dnpp_id' => intval($this->form->getField('dnpp_id')->getValue()),
                    'ict_id' => $this->form->getField('ict_id')->getValue(),
                    'in_all' => $this->form->getField('in_all')->getValue(),
                    'active' => $this->form->getField('active')->getValue(),
                    'main' => $this->form->getField('main')->getValue(),
                    'made_in_moscow' => $this->form->getField('made_in_moscow')->getValue(),
                    'hidden' => $this->form->getField('hidden')->getValue(),
                    'lead' => $this->form->getField('lead')->getValue(),
                    'time' => $this->form->getField('time')->getValue(),
                    'address' => $this->form->getField('address')->getValue(),
                    'site' => $this->form->getField('site')->getValue(),
                    'type' => $this->form->getField('type')->getValue(),
                    'image' => $this->form->getField('image')->getValue(),
                    'lang' => BL::getWorkingLanguage()
                ];

                BackendImsceventsModel::updateEvent($this->id, $item);

                /*
                 * Удаление изображения
                 */
                if ($this->form->getField('delete_image')->isChecked()) {
                    $imagePath = FRONTEND_FILES_PATH . '/Imscevents/images/'.$this->id;
                    self::removeDir($imagePath);
                    BackendImsceventsModel::updateImage($this->id, '');
                } else {

                    /*
                     * Добавление изображения
                     */
                    if($this->id && $this->form->getField('image')->isFilled()){                    
                        $image_orig = urldecode($this->form->getField('image')->getValue());
                        $imagePath = FRONTEND_FILES_PATH . '/Imscevents/images/'.$this->id;
                        
                        if(!stristr($image_orig, 'src/Frontend')) {
                            $image_orig = '/src/Frontend/Files/Imscevents/images/'.$this->id.'/source/'.$image_orig;
                        }

                        if (stripos($image_orig, '/src/Frontend/') === 0) {
                            $image_orig = PATH_WWW . $image_orig;
                        }

                        self::removeDir($imagePath);
                        $filesystem = new Filesystem();

                        $filesystem->mkdir([$imagePath . '/128x128', $imagePath . '/512x', $imagePath . '/1024x']);
                        BackendModel::generateThumbnails($imagePath, $image_orig);
                    }
                }
                
                /*
                 * Переадресация на список мероприятий
                 */
                $this->redirect(
                    BackendModel::createUrlForAction('Index')
                );
            }
        }
    }

    /*
     * Создаём форму для кнопки удаления. Специфика движка
     */
    private function loadDeleteForm(): void
    {
        $deleteForm = $this->createForm(
            ImsceventsDelType::class,
            ['id' => $this->record['id']],
            ['module' => $this->getModule()]
        );
        $this->template->assign('deleteForm', $deleteForm->createView());
    }

    protected function parse(): void
    {
        parent::parse();
        $this->template->assign('image', pathinfo($this->record['image']));
        $this->template->assign('id', $this->record['id']);
    }
}
