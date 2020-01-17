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
    public function rullesCreateNewOrder()
    {
        $this->clearBasketUser();
        $this->sendEmail();
        // TODO: сделать редирект
    }
    public function rullesGetNextOrderNumber() 
    {
        return $this->rullesGetPrifixOrder().$this->get('doctrine')->getRepository(COrder::class)->getNextIdOrderNumber();
    }

    public function rullesGetPrifixOrder()
    {
        return Setting::get('prefix');
    }

    private function clearBasketUser()
    {
        $basket = new Basket();
        $basket->clear();
    }

    private function sendEmail()
    {
        // code
    }
}
?>