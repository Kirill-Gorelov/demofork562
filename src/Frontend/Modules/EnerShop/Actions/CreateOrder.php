<?php

namespace Frontend\Modules\EnerShop\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Backend\Modules\EnerShop\Engine\Classes\Baskets\Basket;
use Backend\Modules\EnerShop\Engine\Classes\Deliverys\Delivery;
use Backend\Modules\EnerShop\Engine\Classes\Order\Order;


class CreateOrder extends FrontendBaseBlock
{
    public $basket_user;
    public $delivery_list;
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();
        $this->loadBasketUser();
        $this->loadDeleveryMethod();
        $this->parse();
    }

    public function parse(){
        $this->template->assign('basket', $this->basket_user);
        $this->template->assign('delivery_list', $this->delivery_list);
    }


    public function loadBasketUser()
    {
        $basket = new Basket();
        $this->basket_user = $basket->get();
        // $this->basket_user = $basket->clear();
        // $this->basket_user = $basket->delete(0);
    }

    public function loadDeleveryMethod()
    {
        $delivery = new Delivery();
        $this->delivery_list = $delivery->getList();
    }

    // TODO: дальше вывести список способов доставки
    // TODO: дальше вывести способы оплаты
    // TODO: И и сделать заказаз через ajax
}
