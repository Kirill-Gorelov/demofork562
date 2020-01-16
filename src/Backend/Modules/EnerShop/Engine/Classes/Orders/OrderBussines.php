<?php 

namespace Backend\Modules\EnerShop\Engine\Classes\Orders;

use Backend\Modules\EnerShop\Domain\Orders\Order as COrder;
use Backend\Modules\EnerShop\Domain\Settings\Setting;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerShop\Engine\Classes\Baskets\Basket;

/**
 * Бизнес логика
 * 
 */
class OrderBussines extends BackendModel
{
    public function rullesGetNextOrderNumber() 
    {
        return $this->rellesGetPrifixOrder().$this->get('doctrine')->getRepository(COrder::class)->getNextIdOrderNumber();
    }

    public function rellesGetPrifixOrder()
    {
        return Setting::get('prefix');
    }

    public function rullesClearBasketUser()
    {
        $basket = new Basket();
        $basket->clear();
    }

    public function rullesSendEmail()
    {
        // code
    }
}
?>