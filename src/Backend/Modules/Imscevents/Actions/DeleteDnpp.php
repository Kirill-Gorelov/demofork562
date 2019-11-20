<?php

namespace Backend\Modules\Imscevents\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Imscevents\Form\DnppCommandDelType;
use Backend\Modules\Imscevents\Engine\Model as BackendImsceventsModel;

/**
 * Class DeleteDnpp Реализация удаления команды загрузки из ДНПП
 * @package Backend\Modules\Imscnews\Actions
 */
class DeleteDnpp extends BackendBaseActionDelete
{
    public function execute(): void
    {
        $deleteForm = $this->createForm(DnppCommandDelType::class, null, ['module' => $this->getModule()]);
        $deleteForm->handleRequest($this->getRequest());
        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            $this->redirect(BackendModel::createUrlForAction('Settings', null, null,
                ['error' => 'something-went-wrong']));
            return;
        }

        $id = (int)$deleteForm->getData()['id'];

        parent::parse();

        BackendImsceventsModel::deleteDnppCommand($id);
        $this->redirect(BackendModel::createUrlForAction('Settings'));
    }
}
