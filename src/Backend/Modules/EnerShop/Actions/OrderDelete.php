<?php

namespace Backend\Modules\EnerShop\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Modules\EnerShop\Engine\Classes\Orders\Order;
use Backend\Core\Engine\Model as BackendModel;

class OrderDelete extends BackendBaseActionDelete
{
    public function execute(): void
    {
        parent::execute();
        $id = $this->getRequest()->get('id');

        if (intval($id) == 0){
            $this->redirect(BackendModel::createUrlForAction('OrderIndex', null, null, ['error' => 'something-went-wrong']));
            return;
        }

        $cls_order = new Order();
        $cls_order->deleteOrderById($id);
        $this->redirect(BackendModel::createUrlForAction('OrderIndex'));
    }
}
