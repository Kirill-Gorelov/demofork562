<?php
namespace Backend\Modules\EnerShop\Engine\Classes\Basket;

use Frontend\Core\Engine\Model as FrontendModel;
use Backend\Modules\EnerIblocks\Engine\CElement;

class Basket{

    public function get(){
        return ['sum_price' => !empty(FrontendModel::getSession()->get('basket')) ? array_sum(array_column(FrontendModel::getSession()->get('basket'), 'item_price')) : 0,
                'sum_price_discount' => '',
                'quantity' => count(array_column(FrontendModel::getSession()->get('basket'), 'id')),
                'list' => FrontendModel::getSession()->get('basket'),
                ];
    }

    public function clear(){
        FrontendModel::getSession()->set('basket', []);
        return $this->get();
    }

    public function addToBasket(array $item){

        if (empty($item)) {
            return false;
        }

        if (empty($item['id'])) {
            return false;
        }

        if (empty($item['quantity'])) {
            return false;
        }

        $element = new CElement;
        $this->element_id = $element->getById($item['id']);

        if (!$this->element_id) {
            return false;
        }

        $basket_user = $this->get();
        $key = array_search($item['id'], array_column($basket_user['list'], 'id'));
        var_dump($key);
        var_dump(intval($key) >= 0);

        
        // if(filter_var($key, FILTER_VALIDATE_INT) && intval($key) >= 0 ){ // потому что найденный элемент может быть на нулевом месте
        if(intval($key) >= 0  && !empty($basket_user['list'][intval($key)])){ // потому что найденный элемент может быть на нулевом месте
            // if(intval($key) >= 0 &&  $key != false){ // потому что найденный элемент может быть на нулевом месте
            $basket_user['list'][intval($key)]['quantity'] += $item['quantity'];
        }else{
            //TODO: решить вопрос со скидками и со свойствами товаров
            $basket_user['list'][] = ['id' => $this->element_id['id'], 
                              'title' => $this->element_id['title'],
                              'image' => $this->element_id['image'],
                              'price' => $this->element_id['price'],
                              'quantity' => $item['quantity'],
                              'discount' => 0,
                              'item_price' => $this->element_id['price'] * $item['quantity'],
                              'discount_price' => 0,
                                ];
        }

        var_dump($basket_user['list']);

        FrontendModel::getSession()->set('basket', $basket_user['list']);
        return true;

    }

    public function delete(){}
    public function update(){}

}
?>