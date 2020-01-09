<?php
namespace Backend\Modules\EnerShop\Engine\Classes\Deliverys;

use Backend\Modules\EnerShop\Domain\DeliveryMethods\DeliveryMethod;
use Backend\Core\Engine\Model as BackendModel;

class Delivery extends BackendModel{

    public function getList(){
        return $this->get('doctrine')->getRepository(DeliveryMethod::class)->getElements();
    }

}
?>