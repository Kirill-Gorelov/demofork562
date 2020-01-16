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

        $this->order = $this->get('doctrine')->getRepository(COrder::class)->getOrderById($id);
        if (empty($this->order)) {
            return null;
        }
        
        $status = $this->get('doctrine')->getRepository(StatusOrder::class)->getElement($this->order['id_status']);
        $pay = $this->get('doctrine')->getRepository(PayMethod::class)->getElement($this->order['id_pay']);
        $delivery = $this->get('doctrine')->getRepository(DeliveryMethod::class)->getElement($this->order['id_delivery']);
        // $product = $this->get('doctrine')->getRepository(COrder::class)->getOrderById($id);
        

        $order_data = [
            'price_deliery' => $this->order['price_deliery'],
            'price' => $this->order['price'],
            'status' => $status,
            'user'=>[
                'fio' => $this->order['user_fio'],
                'email' => $this->order['user_email'],
                'phone' => $this->order['user_phone'],
                'address' => $this->order['user_address'],
                'comment' => $this->order['user_comments'],
            ],
            'delivery' => $delivery,
            'pay' => $pay,
            'product' => [

            ],
        ];

        return $order_data;
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