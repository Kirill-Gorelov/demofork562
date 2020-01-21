<?php

namespace Backend\Modules\EnerEmails\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerEmails\Domain\EnerEmail\EnerEmail;
use Backend\Modules\EnerEmails\Domain\EnerEmail\EnerEmailDelType;


class Delete extends BackendBaseActionDelete
{

    public function execute(): void
    {
        parent::execute();

        $deleteForm = $this->createForm(
            EnerEmailDelType::class,
            null,
            ['module' => $this->getModule()]
        );

        $deleteForm->handleRequest($this->getRequest());
        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            $this->redirect(BackendModel::createUrlForAction('Index', null, null, ['error' => 'something-went-wrong']));

            return;
        }

        $id = $deleteForm->getData()['id'];

        $eneremail = $this->get('doctrine')->getRepository(EnerEmail::class)->findOneById($id);

        $this->get('doctrine')->getRepository(EnerEmail::class)->delete($eneremail);

        $this->redirect(BackendModel::createUrlForAction('Index'));
    }
}
