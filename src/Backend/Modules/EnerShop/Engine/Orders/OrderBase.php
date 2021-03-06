<?php 

namespace Backend\Modules\EnerShop\Engine\Orders;

use Backend\Modules\EnerShop\Domain\Orders\Order as COrder;
use Backend\Modules\EnerShop\Domain\DeliveryMethods\DeliveryMethod;
use Backend\Modules\EnerShop\Domain\PayMethods\PayMethod;
use Backend\Modules\EnerShop\Domain\StatusOrders\StatusOrder;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerShop\Domain\Settings\Setting;
use Backend\Modules\EnerShop\Engine\Baskets\Basket;
use Backend\Modules\EnerEmails\Engine\Email;

class OrderBase extends BackendModel
{
    protected $order;
    public $bussines;

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
        $product = $this->get('doctrine')->getRepository(COrder::class)->getProductsOrder($this->order['id']);
        

        $order_data = [
            'id' => $this->order['id'],
            'order_number' => $this->order['order_number'],
            'price_delivery' => $this->order['price_delivery'],
            'price' => $this->order['price'],
            'status' => $status,
            'user'=>[
                'fio' => $this->order['user_fio'],
                'email' => $this->order['user_email'],
                'phone' => $this->order['user_phone'],
                'address' => $this->order['user_adress'],
                'comment' => $this->order['user_comments'],
            ],
            'delivery' => $delivery,
            'pay' => $pay,
            'product' => $product,
            'date' => $this->order['date'],
            'history' => '',
            'manager_comments' => '',
        ];

        return $order_data;
    }

    public function deleteOrderById($id)
    {
        if (intval($id) == 0) {
            return null;
        }

        $this->get('doctrine')->getRepository(COrder::class)->deleteOrder($id);
        $this->get('doctrine')->getRepository(COrder::class)->deleteProductsOrder($id);
        // TODO: удаление истории статусов заказов
    }

    public function updateOrder(array $data)
    {

    }

    public function getOrdersByUserId()
    {

    }

    protected function clearBasketUser()
    {
        $basket = new Basket();
        $basket->clear();
    }

    protected function sendEmailUser()
    {
        // code
    }

    protected function sendEmailAdmin()
    {
        // TODO: подставить переменную
        return Email::send(1);
    }

    protected function GetNextOrderNumber() 
    {
        return $this->GetPrifixOrder().$this->get('doctrine')->getRepository(COrder::class)->getNextIdOrderNumber();
    }

    protected function GetPrifixOrder()
    {
        return Setting::get('prefix');
    }

}

?>