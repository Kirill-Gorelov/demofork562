<?php

namespace Backend\Modules\EnerShop\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerShop\Domain\StatusOrders\StatusOrder;
use Backend\Modules\EnerShop\Domain\StatusOrders\StatusOrderType;
use Backend\Modules\EnerShop\Domain\StatusOrders\StatusOrderDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

class StatusEdit extends BackendBaseActionEdit {

    protected $id;
    private $Item;

    private function loadItem(): void
    {
        $this->Item = $this->get('doctrine')->getRepository(StatusOrder::class)->findOneById($this->id);
    }

    private function getForm(): Form
    {
        $form = $this->createForm(
            StatusOrderType::class,
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
            StatusOrderDelType::class,
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

        $this->get('doctrine')->getRepository(StatusOrder::class)->update();
        $this->redirect(BackendModel::createUrlForAction('StatusIndex'));
    }

}
