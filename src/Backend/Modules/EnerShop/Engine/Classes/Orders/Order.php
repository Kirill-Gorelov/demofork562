<?
namespace Backend\Modules\EnerShop\Engine\Classes\Orders;
$lang = require ('ru.php');
//префикс заказа
class Order {

    public $order_id;
    public $error;
    public $basket;
    public $user_property;
    public $data_pay;
    public $data_delivery;

    public function setUserProperty(array $user_props)
    {   
        if (empty($user_props)) {
            $this->error[] = $this->getMessage('EMPTY_USER_PROPS');
        }

        $this->user_property = $user_props['list'];
    }

    public function setBasket($basket)
    {
        if (empty($basket['list'])) {
            $this->error[] = $this->getMessage('EMPTY_BASKET');
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

        var_dump($this->user_property);
        var_dump($this->basket);
        var_dump($this->data_delivery);
        var_dump($this->data_pay);

        $this->order_id = 158;
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

    private function getMessage($code){
        if (empty($code)) {
            return '';
        }

        return $code;
        // return !(empty($lang[$code]) ? $lang[$code] : $code;
    }
}
?>