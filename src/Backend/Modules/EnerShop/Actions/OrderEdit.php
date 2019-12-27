<?php

namespace Backend\Modules\EnerShop\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerShop\Domain\Orders\Order;
use Backend\Modules\EnerShop\Domain\Orders\OrderType;
use Backend\Modules\EnerShop\Domain\Orders\OrderDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

class OrderEdit extends BackendBaseActionEdit {

    protected $id;
    private $Item;

    private function loadItem(): void
    {
        $this->Item = $this->get('doctrine')->getRepository(Order::class)->findOneById($this->id);
    }

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

    private function loadDeleteForm(): void
    {
        $deleteForm = $this->createForm(
            OrderDelType::class,
            ['id' => $this->id],
            ['module' => $this->getModule()]
        );
        $this->template->assign('deleteForm', $deleteForm->createView());
    }

    protected function parse(): void
    {
        parent::parse();
        $this->template->assign('id', $this->Item->getId());
    }

    public function execute(): void
    {
        parent::execute();

        $this->id = $this->getRequest()->get('id');

        $this->loadItem();

        $form = $this->getForm();
        // dump($form);
        // die;

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->loadDeleteForm();
            $this->parseForm($form);
            return;
        }

        $this->get('doctrine')->getRepository(Order::class)->update();
        $this->redirect(BackendModel::createUrlForAction('OrderIndex'));
    }

}
