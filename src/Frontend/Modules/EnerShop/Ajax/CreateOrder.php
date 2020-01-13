<?php 
namespace Frontend\Modules\EnerShop\Ajax;

use Frontend\Core\Engine\Base\AjaxAction as FrontendBaseAjaxAction;
use Symfony\Component\HttpFoundation\Response;
use Backend\Modules\EnerShop\Engine\Classes\Baskets\Basket;
use Backend\Modules\EnerShop\Engine\Classes\Pays\Pay;
use Backend\Modules\EnerShop\Engine\Classes\Deliverys\Delivery;
use Backend\Modules\EnerShop\Engine\Classes\Orders\Order;

class CreateOrder extends FrontendBaseAjaxAction{
    
    public function execute():void
    {
        parent::execute();

        $delivery_id = intval($this->getRequest()->get('delivery_id'));
        $pay_id = intval($this->getRequest()->get('pay_id'));

        $user_fname = $this->getRequest()->get('user_fname');
        $user_sname = $this->getRequest()->get('user_sname');
        $user_pname = !empty($this->getRequest()->get('user_pname')) ? $this->getRequest()->get('user_pname') : '';

        $user_address = $this->getRequest()->get('user_address');
        $user_phone = $this->getRequest()->get('user_phone');
        $user_email = $this->getRequest()->get('user_email');

        $order_comment = !empty($this->getRequest()->get('order_comment')) ? $this->getRequest()->get('order_comment') : '';

        if(empty($delivery_id)){
            $this->output(Response::HTTP_OK, [], 'Выберите способ доставки');
            return;
        }

        if(empty($pay_id)){
            $this->output(Response::HTTP_OK, [], 'Выберите способ оплаты');
            return;
        }

        if(empty($user_fname)){
            $this->output(Response::HTTP_OK, [], 'Заполните имя');
            return;
        }

        if(empty($user_sname)){
            $this->output(Response::HTTP_OK, [], 'Заполните фамилию');
            return;
        }

        if(empty($user_address)){
            $this->output(Response::HTTP_OK, [], 'Заполните адрес доставки');
            return;
        }

        if(empty($user_phone)){
            $this->output(Response::HTTP_OK, [], 'Заполните номер телефона');
            return;
        }

        if(empty($user_email)){
            $this->output(Response::HTTP_OK, [], 'Заполните email');
            return;
        }

        if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
            $this->output(Response::HTTP_OK, [], 'Заполните поле email правильно');
            return ;
        }

        //TODO:тут можно сделать еще проверку на неавторизованного пользователя

        $cls_order = new Order();

        $array_user_props = ['user_id' => '',
                            'user_first_name' => $user_fname, 
                            'user_second_name' => $user_sname, 
                            'user_patronymic_name' => $user_pname, 
                            'user_address' => $user_address, 
                            'user_phone' => $user_phone, 
                            'user_email' => $user_email, 
                            'user_comments' => $order_comment];
        $cls_order->setUserProperty($array_user_props);

        $basket = new Basket();
        $basket_user = $basket->get();
        $cls_order->setBasket($basket_user); //кладем товары

        // $delivery = new Delivery();
        // $delivery_system = $delivery->getId($delivery_id);
        // $cls_order->setDelivery($delivery_system);//записываем способ доставки
        $cls_order->setDelivery($delivery_id);//записываем способ доставки

        // $pay = new Pay();
        // $pay_system = $pay->getId($pay_id);
        // $cls_order->setPay($pay_system); // записываем способ оплаты
        $cls_order->setPay($pay_id); // записываем способ оплаты

        if (empty($cls_order->getErrors())) {
            $cls_order->create();// создаем заказ
        }

        if(empty($cls_order->getErrors()) && $cls_order->getOrderId() > 0){
            $this->output(Response::HTTP_OK, ['order_id' => $cls_order->getOrderId()], 'Заказ сохранен');
            return;
        }

        $this->output(Response::HTTP_OK, [$cls_order->getErrors()], 'Ошибка создания заказа');
        return;

    }
}

?>