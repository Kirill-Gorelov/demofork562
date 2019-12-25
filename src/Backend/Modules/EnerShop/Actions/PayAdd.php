<?php

namespace Backend\Modules\EnerShop\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerShop\Domain\PayMethods\PayMethod;
use Backend\Modules\EnerShop\Domain\PayMethods\PayMethodType;
use Backend\Modules\EnerShop\Domain\PayMethods\PayMethodDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

class PayAdd extends BackendBaseActionEdit {

    private $PayMethod;

    private function getForm(): Form
    {
        $form = $this->createForm(
            PayMethodType::class,
            $this->PayMethod
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

    private function create(Form $form): bool
    {
        $item = $form->getData();

        $this->get('doctrine')->getRepository(PayMethod::class)->add($item);

        return true;
    }

    public function execute(): void
    {
        parent::execute();

        $form = $this->getForm();
        // dump($form);
        // die;

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->parseForm($form);
            return;
        }

        $this->create($form);
        $this->redirect(BackendModel::createUrlForAction('PayIndex'));
    }

}
