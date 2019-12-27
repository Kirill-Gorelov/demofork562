<?php

namespace Backend\Modules\EnerShop\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerShop\Domain\StatusOrders\StatusOrder;
use Backend\Modules\EnerShop\Domain\StatusOrders\StatusOrderType;
use Backend\Modules\EnerShop\Domain\StatusOrders\StatusOrderDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

class StatusAdd extends BackendBaseActionEdit {

    private $Item;

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

    private function create(Form $form): bool
    {
        $item = $form->getData();

        $this->get('doctrine')->getRepository(StatusOrder::class)->add($item);

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
        $this->redirect(BackendModel::createUrlForAction('StatusIndex'));
    }

}
