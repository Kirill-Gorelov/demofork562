<?
namespace Backend\Modules\EnerShop\Engine\Classes\Orders;

//префикс заказа
class Order {

    public $order_id;
    public $error;

    public function setUserProperty(array $user_props)
    {   
        $this->order_id = 22;
        return $this->order_id;
    }

    public function setBasket($basket)
    {
        $this->order_id;
    }

    public function setDelivery($id)
    {
        $this->order_id;
    }

    public function setPay($id)
    {
        $this->order_id;
    }

    public function create()
    {
        if(!empty($this->error)){
            // исключение
        }

        

    }

    public function getErrors()
    {
        return $this->error;
    }

    public function getOrderId()
    {
        //а если еще не создан заказа???
        return $this->order_id;
    }
}
?>