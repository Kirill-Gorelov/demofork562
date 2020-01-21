<?php

namespace Backend\Modules\EnerEmails\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Modules\EnerEmails\Domain\EnerEmail\EnerEmail;
use Backend\Modules\EnerEmails\Domain\EnerEmail\EnerEmailType;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;


class Add extends BackendBaseActionAdd
{

    private function getForm(): Form
    {
        $form = $this->createForm(
            EnerEmailType::class,
            new EnerEmail()
        );

        $form->handleRequest($this->getRequest());

        return $form;
    }

    private function parseForm(Form $form): void
    {
        $this->template->assign('form', $form->createView());

        $this->parse();
        $this->display();
    }

    private function createEnerEmail(Form $form): bool
    {
        $eneremail = $form->getData();
		
        $this->get('doctrine')->getRepository(EnerEmail::class)->add($eneremail);

        return true;
    }

    public function execute(): void
    {
        parent::execute();

        $form = $this->getForm();

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->parseForm($form);
            return;
        }

        $this->createEnerEmail($form);
        $this->redirect(BackendModel::createUrlForAction('Index'));
    }
}
