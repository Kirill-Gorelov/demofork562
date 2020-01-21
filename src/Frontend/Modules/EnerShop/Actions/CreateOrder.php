<?php

namespace Frontend\Modules\EnerShop\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Backend\Modules\EnerShop\Engine\Baskets\Basket;
use Backend\Modules\EnerShop\Engine\Deliverys\Delivery;
use Backend\Modules\EnerShop\Engine\Pays\Pay;
use Backend\Modules\EnerShop\Engine\Order\Order;
use Frontend\Modules\Profiles\Engine\Authentication;


class CreateOrder extends FrontendBaseBlock
{
    public $basket_user;
    public $delivery_list;
    public $pay_list;
    public $status_user;
    public $profile;

    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();
        $this->loadBasketUser();
        $this->loadDeleveryMethod();
        $this->loadPayMethod();
        $this->checkActiveUser();
        $this->parse();
    }

    public function parse(){
        $this->template->assign('basket', $this->basket_user);
        $this->template->assign('delivery_list', $this->delivery_list);
        $this->template->assign('pay_list', $this->pay_list);
        $this->template->assign('statususer', $this->status_user);
        if(Authentication::isLoggedIn()){
            $this->profile = Authentication::getProfile();
            $this->template->assign('profile', $this->profile->getId());
        }
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

    public function loadPayMethod()
    {
        $pay = new Pay();
        $this->pay_list = $pay->getList();
    }

    public function checkActiveUser()
    {
        if (Authentication::isLoggedIn()) {
            $this->status_user = true;
        }else{
            $this->status_user = false;
        }
    }

}
