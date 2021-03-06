<?php

namespace Backend\Modules\EnerEmails\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerEmails\Domain\EnerEmail\EnerEmail;
use Backend\Modules\EnerEmails\Domain\EnerEmail\EnerEmailType;
use Backend\Modules\EnerEmails\Domain\EnerEmail\EnerEmailDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Modules\EnerEmails\Engine\Email;

class Edit extends BackendBaseActionEdit {

    protected $id;

    private $eneremail;

    private function loadEnerEmail(): void
    {
        $this->eneremail = $this->get('doctrine')->getRepository(EnerEmail::class)->findOneById($this->id);

    }

    private function getForm(): Form
    {
        $form = $this->createForm(
            EnerEmailType::class,
            $this->eneremail
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

    private function loadDeleteForm(): void
    {
        $deleteForm = $this->createForm(
            EnerEmailDelType::class,
            ['id' => $this->id],
            ['module' => $this->getModule()]
        );
        $this->template->assign('deleteForm', $deleteForm->createView());
    }

    protected function parse(): void
    {
        parent::parse();
        $this->template->assign('id', $this->eneremail->getId());
    }

    public function execute(): void
    {
        parent::execute();
		
		
        $this->id = $this->getRequest()->get('id');
		
        $this->loadEnerEmail();

        $form = $this->getForm();

		$test_variable = [
            'emai_user3' => 'emai_user3@emai_user3.ru',
            'email_user123456' => 'email_user@email_user.ru',
            'emai_user2' => 'emai_user2@emai_user2.ru',
            'nex_subject' => 'дополнение к теме',
        ];
        Email::send(1, $test_variable);
		
        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->loadDeleteForm();
            $this->parseForm($form);
            return;
        }


        $this->get('doctrine')->getRepository(EnerEmail::class)->update();
        $this->redirect(BackendModel::createUrlForAction('Index'));
    }

}
