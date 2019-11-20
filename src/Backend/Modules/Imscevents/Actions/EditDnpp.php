<?php

namespace Backend\Modules\Imscevents\Actions;

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Language\Language as BL;
use Backend\Modules\Imscevents\Engine\Model as BackendImsceventsModel;
use Backend\Modules\Imscevents\Form\DnppCommandDelType;

/**
 * Class EditDnpp Редактирование команд загрузки новостей из ДНПП
 * @package Backend\Modules\Imscnews\Actions
 */
class EditDnpp extends BackendBaseActionEdit
{
    /** @var array Доступны типы шлюзов обмена данными. */
    private static $gates = [
        'DNPP' => 'DNPP',
        'ICT' => 'ICT'
    ];

    public function execute(): void
    {
        $this->id = $this->getRequest()->query->getInt('id');

        if ($this->id !== 0) {
            parent::execute();

            $this->record = BackendImsceventsModel::getDnppCommand($this->id);
            $this->loadForm();
            $this->loadDeleteForm();
            $this->validateForm();
            $this->parse();
            $this->display();
        } else {
            $this->redirect(BackendModel::createUrlForAction('Settings'));
        }
    }

    /**
     * Создание формы редактирования
     */
    private function loadForm(): void
    {
        $this->form = new BackendForm('edit');
        $this->form->addText('description', $this->record['description'], 250,
            'form-control description', 'form-control danger description');
        $this->form->addText('command', $this->record['command'], 250,
            'form-control command', 'form-control danger command');
        $this->form->addDropdown('gate', self::$gates, $this->record['gate_id'], false,
            'form-control gate', 'form-control danger gate');
        $this->form->addText('image_folder', $this->record['image_folder'], 500,
            'form-control image_folder', 'form-control danger image_folder');
        $this->form->addText('password', $this->record['password'], 50,
            'form-control image_folder', 'form-control danger image_folder');
    }

    /**
     * Создание формы удаления команды ДНПП
     */
    private function loadDeleteForm(): void
    {
        $deleteForm = $this->createForm(
            DnppCommandDelType::class,
            ['id' => $this->record['id']],
            ['module' => $this->getModule()]
        );
        $this->template->assign('deleteForm', $deleteForm->createView());
    }

    private function validateForm(): void
    {
        if ($this->form->isSubmitted()) {
            $this->form->cleanupFields();

            $this->form->getField('description')->isFilled(BL::err('FieldIsRequired'));
            $this->form->getField('command')->isFilled(BL::err('FieldIsRequired'));
            $this->form->getField('gate')->isFilled(BL::err('FieldIsRequired'));

            if ($this->form->isCorrect()) {
                $item = [
                    'id' => $this->id,
                    'description' => $this->form->getField('description')->getValue(),
                    'command' => $this->form->getField('command')->getValue(),
                    'gate_id' => $this->form->getField('gate')->getValue(),
                    'image_folder' => $this->form->getField('image_folder')->getValue(),
                    'password' => $this->form->getField('password')->getValue(),
                    'language' => BL::getWorkingLanguage()
                ];

                BackendImsceventsModel::updateDNPPCommand($item);
                $this->redirect(BackendModel::createUrlForAction('Settings'));
            }
        }
    }

    protected function parse(): void
    {
        parent::parse();

        $this->template->assign('description', $this->record['description']);
    }
}
