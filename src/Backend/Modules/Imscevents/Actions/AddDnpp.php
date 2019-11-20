<?php

namespace Backend\Modules\Imscevents\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Language\Language as BL;
use Backend\Modules\Imscevents\Engine\Model as BackendImsceventsModel;

/**
 * Class AddDnpp Добавление команды загрузки из ДНПП
 * @package Backend\Modules\Imscnews\Actions
 */
class AddDnpp extends BackendBaseActionAdd
{
    /** @var array Доступны типы шлюзов обмена данными. */
    private static $gates = [
        'DNPP' => 'DNPP',
        'ICT' => 'ICT'
    ];

    public function execute(): void
    {
        parent::execute();

        $this->loadForm();
        $this->validateForm();
        $this->parse();
        $this->display();
    }

    /**
     * Формирование формы
     */
    private function loadForm(): void
    {
        $this->form = new BackendForm('add');
        $this->form->addText('description', null, 250,
            'form-control description', 'form-control danger description');
        $this->form->addText('command', null, 250,
            'form-control command', 'form-control danger command');
        $this->form->addDropdown('gate', self::$gates, null, false, 'form-control gate');
        $this->form->addText('image_folder', '', 500,
            'form-control image_folder', 'form-control danger image_folder');
        $this->form->addText('password', $this->record['password'], 50,
            'form-control image_folder', 'form-control danger image_folder');
    }

    /**
     * Проверка формы и сохранение данных в БД.
     */
    private function validateForm(): void
    {
        if ($this->form->isSubmitted()) {
            $this->form->cleanupFields();

            $this->form->getField('description')->isFilled(BL::err('FieldIsRequired'));
            $this->form->getField('command')->isFilled(BL::err('FieldIsRequired'));
            $this->form->getField('gate')->isFilled(BL::err('FieldIsRequired'));

            if ($this->form->isCorrect()) {
                $item = [
                    'description' => $this->form->getField('description')->getValue(),
                    'command' => $this->form->getField('command')->getValue(),
                    'gate_id' => $this->form->getField('gate')->getValue(),
                    'image_folder' => $this->form->getField('image_folder')->getValue(),
                    'password' => $this->form->getField('password')->getValue(),
                    'language' => BL::getWorkingLanguage()
                ];
                BackendImsceventsModel::addDNPPCommand($item);

                $this->redirect(BackendModel::createUrlForAction('Settings'));
            }
        }
    }

    protected function parse(): void
    {
        parent::parse();
    }
}
