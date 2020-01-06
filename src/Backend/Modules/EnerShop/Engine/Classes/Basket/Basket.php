<?php
namespace Backend\Modules\EnerShop\Engine\Classes\Basket;

use Frontend\Core\Engine\Model as FrontendModel;

class Basket{
    public function get(){}
    public function addToBasket(){
        FrontendModel::getSession()->set('test', 'test_value_sdfghjbv');
        var_export(FrontendModel::getSession()->get('test'));
        // var_export(FrontendModel::getSession()->getId());
    }
    public function delete(){}
    public function update(){}
}
?>