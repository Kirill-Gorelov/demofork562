<?
namespace Backend\Modules\EnerShop\Engine\Orders;

use Backend\Modules\EnerShop\Domain\Orders\Order as COrder;
use Backend\Modules\EnerShop\Engine\Baskets\Basket;
use Backend\Modules\EnerShop\Domain\Settings\Setting;
use Backend\Modules\EnerShop\Engine\Orders\OrderBase;
use Backend\Modules\EnerShop\Domain\DeliveryMethods\DeliveryMethod;

class Order extends OrderBase{

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

        //TODO: а они точно нужны-то???
        // if (empty($user_props['user_first_name'])) {
        //     $this->error[] = 'Не заполнено имя пользователя';
        // }

        // if (empty($user_props['user_second_name'])) {
        //     $this->error[] = 'Не заполнено имя пользователя';
        // }

        // if (empty($user_props['user_patronymic_name'])) {
        //     $this->error[] = 'Не заполнено имя пользователя';
        // }

        // if (empty($user_props['user_address'])) {
        //     $this->error[] = 'Не заполнено имя пользователя';
        // }

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

        $r = $this->get('doctrine')->getRepository(DeliveryMethod::class)->getElement($data);
        if (empty($r)) {
            $this->error[] = 'Способа доставки не существует.';
        }

        $item = [
            'id' => !empty($r['id']) ? $data : 0,
            'price' => !empty($r['id']) ?  $r['price'] : 0
        ];
        $this->data_delivery = $item;
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

        try {
            $id = $this->get('doctrine')->getRepository(COrder::class)->createOrder($this->prepareArrayOrder());
            $this->get('doctrine')->getRepository(COrder::class)->insertOrderProduct($id, $this->basket);
            // $this->get('doctrine')->getRepository(COrder::class)->insertDeliveryData($this->data_delivery);
            // $this->get('doctrine')->getRepository(COrder::class)->insertPayData($this->data_pay);
        } catch (Exception $e) {
            $this->error[] = $e->getMessage();
            return;
        }

        if (intval($id) > 0) {
            //$this->clearBasketUser();
            // $this->sendEmailUser();
            $this->sendEmailAdmin();
        }

        // var_dump($this->user_property);
        // var_dump($this->basket);
        // var_dump($this->data_delivery);
        // var_dump($this->data_pay);

        $this->order_id = $id;

        
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

    public function getOrderNumber()
    {
        if (empty($this->order_id)) {
            throw new Exception("Заказ не существует");
        }
        
        return $this->GetPrifixOrder().$this->order_id;
    }

    private function prepareArrayOrder()
    {
        $item = ['order_number' => $this->GetNextOrderNumber(),
            'id_user' => !empty($this->user_property['user_id']) ? $this->user_property['user_id'] : '',
            'id_delivery' => $this->data_delivery['id'],
            'id_pay' => $this->data_pay,
            'id_status' => 1,//не оплачен
            'price_delivery' => $this->data_delivery['price'],
            'price' => $this->order_price,
            'user_comments' => $this->user_property['user_comments'],
            'user_adress' => $this->user_property['user_address'],
            'user_fio' => sprintf("%s %s %s",$this->user_property['user_second_name'],$this->user_property['user_first_name'],$this->user_property['user_patronymic_name']),
            'user_email' => $this->user_property['user_email'],
            'user_phone' => $this->user_property['user_phone'],
        ];

        return $item;
    }

}
?>