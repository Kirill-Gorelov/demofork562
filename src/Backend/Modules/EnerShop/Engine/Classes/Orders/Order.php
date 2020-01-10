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
        $this->error;
    }

    public function setDelivery($id)
    {
        $this->error;
    }

    public function setPay($id)
    {
        $this->error;
    }

    public function create()
    {
        if(!empty($this->error)){
            // исключение
        }

        

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
}
?>