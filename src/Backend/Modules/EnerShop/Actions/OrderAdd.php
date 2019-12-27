<?php

namespace Backend\Modules\EnerShop\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerShop\Domain\Orders\Order;
use Backend\Modules\EnerShop\Domain\Orders\OrderType;
use Backend\Modules\EnerShop\Domain\Orders\OrderDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

class OrderAdd extends BackendBaseActionEdit {

    private $Item;

    private function getForm(): Form
    {
        $form = $this->createForm(
            OrderType::class,
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

        $this->get('doctrine')->getRepository(Order::class)->add($item);

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
        $this->redirect(BackendModel::createUrlForAction('OrderIndex'));
    }

}
