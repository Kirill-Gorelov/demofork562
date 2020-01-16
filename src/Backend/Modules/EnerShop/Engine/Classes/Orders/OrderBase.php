<?php 

namespace Backend\Modules\EnerShop\Engine\Classes\Orders;

use Backend\Modules\EnerShop\Domain\Orders\Order as COrder;
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

       return $this->order = $this->get('doctrine')->getRepository(COrder::class)->getOrderById($id);
    }

    public function deleteOrderById()
    {
        if (intval($id) == 0) {
            return null;
        }

        return;
    }

    public function updateOrder(array $data)
    {

    }
}

?>