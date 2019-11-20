<?php

namespace Backend\Modules\Imscevents\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Symfony\Component\Filesystem\Filesystem;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Modules\Imscevents\Engine\Model as BackendImsceventsModel;
use Backend\Modules\Imscoii\Engine\Model as BackendImscoiiModel;
use Backend\Core\Language\Language as BL;

/*
 * Контроллер добавления мероприятия
 */
class Addevent extends BackendBaseActionAdd {
    /** @var array Список типов объектов */
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

        parent::execute();

        $this->loadForm();

        $this->validateForm();

        $this->parse();
        $this->display();

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
        $this->form = new BackendForm('add');

        $this->form->addText('title', null, 512, 'form-control title', 'form-control danger title');

        $this->form->addEditor('short', null, 'form-control short', 'form-control danger short');

        $this->form->addText('lead', null, 512, 'form-control lead', 'form-control danger lead');

        $this->form->addText('time', null, 255, 'form-control time', 'form-control danger time');

        $this->form->addText('address', null, 255, 'form-control address', 'form-control danger address');

        $this->form->addText('site', null, 255, 'form-control site', 'form-control danger site');

        $this->form->addDropdown('type', $this->types, null, false, 'form-control type',
            'form-control danger type');

        $this->form->addEditor('description', null, 'form-control description', 'form-control danger title');

        $this->form->addDropdown('type', $this->types, 1, false, 'form-control type', 'form-control danger type');

        $this->form->addText('image', null, null, 'form-control image', 'form-control danger image');

        $this->form->addText('dnpp_id', null, 255, 'form-control dnpp_id', 'form-control danger dnpp_id');

        $this->form->addText('ict_id', null, 255, 'form-control ict_id', 'form-control danger ict_id');

        $this->form->addDate('date', null, null, strtotime("NOW"),null, 'form-control date', 'form-control danger date');

        $this->form->addDate('enddate', null, null, strtotime("NOW"),null, 'form-control enddate', 'form-control danger date');

        $this->innoObjectTypes = BackendImscoiiModel::getInnoObjectTypesFilter(BL::getWorkingLanguage());
        $this->form->addDropdown('innoObjectTypeId', $this->innoObjectTypes);

        $this->form->addText('oii_id', null, 255, 'form-control oii_id', 'form-control danger oii_id');

        $this->form->addText('oii_name', null, 255, 'form-control oii_name', 'form-control danger oii_name');


        $this->form->addCheckbox('active', 1);

        $this->form->addCheckbox('in_all', 1);

        $this->form->addCheckbox('main', 0);

        $this->form->addCheckbox('made_in_moscow', 0);

        $this->form->addCheckbox('hidden', 0);
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

                $id = BackendImsceventsModel::addEvent($item);

                /*
                 * Добавление изображения
                 */

                if($id && $this->form->getField('image')->isFilled())
                {
                    $image_orig = $this->form->getField('image')->getValue();
                    $imagePath = FRONTEND_FILES_PATH . '/Imscevents/images/'.$id;
                    $filesystem = new Filesystem();

                    $filesystem->mkdir([$imagePath . '/128x128', $imagePath . '/512x']);
                    BackendModel::generateThumbnails($imagePath, PATH_WWW . $image_orig);
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

    protected function parse(): void
    {
        parent::parse();
    }
}
