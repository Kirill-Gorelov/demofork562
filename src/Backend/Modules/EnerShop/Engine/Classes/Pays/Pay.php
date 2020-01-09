<?php
namespace Backend\Modules\EnerShop\Engine\Classes\Pays;

use Backend\Modules\EnerShop\Domain\PayMethods\PayMethod;
use Backend\Core\Engine\Model as BackendModel;

class Pay extends BackendModel{

    public function getList(){
        return $this->get('doctrine')->getRepository(PayMethod::class)->getElements();
    }

}
?>