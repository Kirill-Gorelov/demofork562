<?php 

namespace Backend\Modules\EnerShop\Engine\Classes\Orders;

use Backend\Modules\EnerShop\Domain\Orders\Order as COrder;
use Backend\Modules\EnerShop\Domain\Settings\Setting;
use Backend\Core\Engine\Model as BackendModel;

/**
 * Бизнес логика
 * 
 */
class OrderBussines extends BackendModel
{
    public function rullesGetNextOrderNumber() 
    {
        return Setting::get('prefix').$this->get('doctrine')->getRepository(COrder::class)->getNextIdOrderNumber();
    }
}
?>