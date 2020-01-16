<?php 

namespace Backend\Modules\EnerShop\Engine\Classes\Orders;

use Backend\Modules\EnerShop\Domain\Orders\Order;
use Backend\Modules\EnerShop\Domain\DeliveryMethods\DeliveryMethod;
use Backend\Modules\EnerShop\Domain\PayMethods\PayMethod;
use Backend\Modules\EnerShop\Domain\StatusOrders\StatusOrder;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerShop\Domain\Settings\Setting;

class OrderBase extends BackendModel
{
    protected $order;
    public function getOrderById($id)
    {
        if (intval($id) == 0) {
            return null;
        }

        return $this->order = $this->get('doctrine')->getRepository(Order::class)->getOrderById($id);
    }
}

?>