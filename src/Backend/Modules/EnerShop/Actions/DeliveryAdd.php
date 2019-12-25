<?php

namespace Backend\Modules\EnerShop\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerShop\Domain\DeliveryMethods\DeliveryMethod;
use Backend\Modules\EnerShop\Domain\DeliveryMethods\DeliveryMethodType;
use Backend\Modules\EnerShop\Domain\DeliveryMethods\DeliveryMethodDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

class DeliveryAdd extends BackendBaseActionEdit {

    private $Item;

    private function getForm(): Form
    {
        $form = $this->createForm(
            DeliveryMethodType::class,
            $this->Item
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

        $this->get('doctrine')->getRepository(DeliveryMethod::class)->add($item);

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
        $this->redirect(BackendModel::createUrlForAction('DeliveryIndex'));
    }

}
