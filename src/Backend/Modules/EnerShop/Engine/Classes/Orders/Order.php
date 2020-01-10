<?
namespace Backend\Modules\EnerShop\Engine\Classes\Orders;

use Backend\Modules\EnerShop\Domain\Orders\Order as COrder;
use Backend\Modules\EnerShop\Engine\Classes\Baskets\Basket;
// $lang = require ('ru.php');
//префикс заказа
class Order {

    private $order_id;
    private $error;
    private $basket;
    private $user_property;
    private $data_pay;
    private $data_delivery;

    public function setUserProperty(array $user_props)
    {   
        if (empty($user_props)) {
            $this->error[] = $this->getMessageError('EMPTY_USER_PROPS');
        }

        $this->user_property = $user_props;
    }

    public function setBasket($basket)
    {
        if (empty($basket['list'])) {
            $this->error[] = $this->getMessageError('EMPTY_BASKET');
        }

        $this->basket = $basket['list'];
    }

    public function setDelivery($data)
    {
        if (empty($data)) {
            $this->error[] = 'Не выбран способ доставки';
        }

        $this->data_delivery = $data;
    }

    public function setPay($data)
    {
        if (empty($data)) {
            $this->error[] = 'Не выбран способ оплаты';
        }

        $this->data_pay = $data;
    }

    public function create()
    {
        if(!empty($this->error)){
            throw new Exception("Исправьте ошибки");
        }

        //TODO: нужно учитывать перфикс заказа

        try {
            //code...
        } catch (Exception $e) {
            $id = $this->get('doctrine')->getRepository(COrder::class)->insertUserProperty($this->user_property);
            $this->get('doctrine')->getRepository(COrder::class)->insertOrderProduct($this->basket);
            $this->get('doctrine')->getRepository(COrder::class)->insertDeliveryData($this->data_delivery);
            $this->get('doctrine')->getRepository(COrder::class)->insertPayData($this->data_pay);
            $this->error[] = $e->getMessage();
            return;
        }

        // var_dump($this->user_property);
        // var_dump($this->basket);
        // var_dump($this->data_delivery);
        // var_dump($this->data_pay);

        $this->order_id = $id;

        // $basket = new Basket();
        // $basket->clear();
        return $this->getOrderId();
    }

    public function getErrors()
    {
        return !empty($this->error) ? $this->error : [];
    }

    public function getOrderId()
    {
        if (empty($this->order_id)) {
            throw new Exception("Заказ не существует");
        }

        
        return $this->order_id;
    }

    private function getMessageError($code){
        if (empty($code)) {
            return '';
        }

        return $code;
        // return !(empty($lang[$code]) ? $lang[$code] : $code;
    }
}
?>