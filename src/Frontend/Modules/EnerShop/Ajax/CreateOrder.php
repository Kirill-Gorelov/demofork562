<?php 
namespace Frontend\Modules\EnerShop\Ajax;

use Frontend\Core\Engine\Base\AjaxAction as FrontendBaseAjaxAction;
use Symfony\Component\HttpFoundation\Response;
use Backend\Modules\EnerShop\Engine\Classes\Baskets\Basket;
use Backend\Modules\EnerShop\Engine\Classes\Pays\pay;
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
        $user_pname = $this->getRequest()->get('user_pname');

        $user_address = $this->getRequest()->get('user_address');
        $user_phone = $this->getRequest()->get('user_phone');
        $user_email = $this->getRequest()->get('user_email');

        $order_comment = $this->getRequest()->get('order_comment');

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
        


    }
}

?>