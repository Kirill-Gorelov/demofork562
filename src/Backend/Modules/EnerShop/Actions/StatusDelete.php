<?php

namespace Backend\Modules\EnerShop\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerShop\Domain\StatusOrders\StatusOrder;
use Backend\Modules\EnerShop\Domain\StatusOrders\StatusOrderDelType;

class StatusDelete extends BackendBaseActionDelete
{

    public function execute(): void
    {
        parent::execute();

        $deleteForm = $this->createForm(
            StatusOrderDelType::class,
            null,
            ['module' => $this->getModule()]
        );

        $deleteForm->handleRequest($this->getRequest());
        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            $this->redirect(BackendModel::createUrlForAction('Index', null, null, ['error' => 'something-went-wrong']));

            return;
        }

        $id = $deleteForm->getData()['id'];

        $item = $this->get('doctrine')->getRepository(StatusOrder::class)->findOneById($id);

        $this->get('doctrine')->getRepository(StatusOrder::class)->delete($item);

        $this->redirect(BackendModel::createUrlForAction('StatusIndex'));
    }
}
