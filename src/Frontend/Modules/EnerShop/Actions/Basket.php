<?php

namespace Frontend\Modules\EnerShop\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Backend\Modules\EnerShop\Engine\Classes\Baskets\Basket as CBasket;


class Basket extends FrontendBaseBlock
{
    public $basket_user;
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();
        $this->loadBasketUser();
        $this->parse();
    }

    public function parse(){
        $this->template->assign('basket', $this->basket_user);
    }


    public function loadBasketUser()
    {
        $basket = new CBasket();
        $this->basket_user = $basket->get();
        // $this->basket_user = $basket->clear();
        // $this->basket_user = $basket->delete(0);
    }
}
