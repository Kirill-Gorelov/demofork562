<?
namespace Backend\Modules\EnerShop\Engine\Classes\Orders;

use Backend\Modules\EnerShop\Domain\Orders\Order as COrder;
use Backend\Modules\EnerShop\Engine\Classes\Baskets\Basket;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerShop\Domain\Settings\Setting;
// $lang = require ('ru.php');
//префикс заказа
class Order extends BackendModel{

    private $order_id;
    private $error;
    private $basket;
    private $user_property;
    private $data_pay;
    private $data_delivery;
    private $order_price;

    public function setUserProperty(array $user_props)
    {   
        if (empty($user_props)) {
            $this->error[] = 'Не заполнены поля пользователя';
        }

        $this->user_property = $user_props;
    }

    public function setBasket($basket)
    {
        if (empty($basket['list'])) {
            $this->error[] = 'Корзина пуста';
        }

        $this->order_price = $basket['sum_price'];
        $this->basket = $basket['list'];
    }

    public function setDelivery($data)
    {
        if (empty($data) && intval($data) == 0) {
            $this->error[] = 'Не выбран способ доставки';
        }

        // TODO: можно проверить существование такого способа доставки

        $this->data_delivery = $data;
    }

    public function setPay($data)
    {
        if (empty($data) && intval($data) == 0) {
            $this->error[] = 'Не выбран способ оплаты';
        }
        // TODO: можно проверить существование такого способа оплаты

        $this->data_pay = $data;
    }

    public function create()
    {
        if(!empty($this->error)){
            throw new Exception("Исправьте ошибки");
        }

        //TODO: нужно учитывать перфикс заказа

        try {
            $id = $this->get('doctrine')->getRepository(COrder::class)->createOrder($this->prepareArrayOrder());
            $this->get('doctrine')->getRepository(COrder::class)->insertOrderProduct($id, $this->basket);
            // $this->get('doctrine')->getRepository(COrder::class)->insertDeliveryData($this->data_delivery);
            // $this->get('doctrine')->getRepository(COrder::class)->insertPayData($this->data_pay);
        } catch (Exception $e) {
            $this->error[] = $e->getMessage();
            return;
        }

        // var_dump($this->user_property);
        // var_dump($this->basket);
        // var_dump($this->data_delivery);
        // var_dump($this->data_pay);

        $this->order_id = $id;

        // TODO:очистить корзину пользователя 
        // $this->clearBasketUser();
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

    private function prepareArrayOrder()
    {
        $item = ['order_number' => Setting::get('prefix').rand(100, 500),
            'id_user' => !empty($this->user_property['user_id']) ? $this->user_property['user_id'] : '',
            'id_delivery' => $this->data_delivery,
            'id_pay' => $this->data_pay,
            'id_status' => '',
            'price_delivery' => '',
            'price' => $this->getPriceOrder(),
            'user_comments' => $this->user_property['user_comments'],
            'user_adress' => $this->user_property['user_address'],
            'user_fio' => sprintf("%s %s %s",$this->user_property['user_second_name'],$this->user_property['user_first_name'],$this->user_property['user_patronymic_name']),
            'user_email' => $this->user_property['user_email'],
            'user_phone' => $this->user_property['user_phone'],
        ];

        return $item;

    }

    private function clearBasketUser()
    {
        $basket = new Basket();
        $basket->clear();
    }

    private function getPriceOrder()
    {
        return $this->order_price;
    }
}
?>