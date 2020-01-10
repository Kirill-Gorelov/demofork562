<?php 
namespace Frontend\Modules\EnerShop\Ajax;

use Frontend\Core\Engine\Base\AjaxAction as FrontendBaseAjaxAction;
use Symfony\Component\HttpFoundation\Response;
use Backend\Modules\EnerShop\Engine\Classes\Baskets\Basket;
use Backend\Modules\EnerShop\Engine\Classes\Pays\pay;
use Backend\Modules\EnerShop\Engine\Classes\Deliverys\Delivery;

class CreateOrder extends FrontendBaseAjaxAction{
    
    public function execute():void
    {
        parent::execute();

        $delivery_id = intval($this->getRequest()->get('delivery_id'));
        $pay_id = intval($this->getRequest()->get('pay_id'));

        if(empty($delivery_id)){
            $this->output(Response::HTTP_OK, [], 'Выберите способ доставки');
            return;
        }

        if(empty($pay_id)){
            $this->output(Response::HTTP_OK, [], 'Выберите способ оплаты');
            return;
        }

    }
}

?>