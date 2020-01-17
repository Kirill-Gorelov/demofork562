<?php
namespace Backend\Modules\EnerShop\Engine\Pays;

use Backend\Modules\EnerShop\Domain\PayMethods\PayMethod;
use Backend\Core\Engine\Model as BackendModel;

class Pay extends BackendModel{

    public function getList(){
        return $this->get('doctrine')->getRepository(PayMethod::class)->getElements();
    }

    public function getId($id){

        if (intval($id) == 0) {
            return false;
        }

        return $this->get('doctrine')->getRepository(PayMethod::class)->getElement($id);
    }
}
?>