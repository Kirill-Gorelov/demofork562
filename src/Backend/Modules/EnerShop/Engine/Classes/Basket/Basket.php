<?php
namespace Backend\Modules\EnerShop\Engine\Classes\Basket;

use Frontend\Core\Engine\Model as FrontendModel;
use Backend\Modules\EnerIblocks\Engine\CElement;

class Basket{

    public function get(){
        return ['sum' => !empty(FrontendModel::getSession()->get('basket')) ? array_sum(array_column(FrontendModel::getSession()->get('basket'), 'item_price')) : 0,
                'quantity' => count(array_column(FrontendModel::getSession()->get('basket'), 'id')),
                'list' => FrontendModel::getSession()->get('basket'),
                ];
    }

    public function clear(){
        FrontendModel::getSession()->set('basket', []);
        return $this->get();
    }

    public function addToBasket(array $item){

        $element = new CElement;
        $this->element_id = $element->getById($item['id']);

        if (!$this->element_id) {
            return false;
        }

        $basket_user = $this->get();
        $quantity = 1;
        //TODO: решить вопрос со скидками и со свойствами товаров
        $basket_user['list'][] = ['id' => $this->element_id['id'], 
                          'title' => $this->element_id['title'],
                          'image' => $this->element_id['image'],
                          'price' => $this->element_id['price'],
                          'quantity' => $quantity,
                          'discount' => 0,
                          'item_price' => intval($this->element_id['price'] + $quantity),
                          'discount_price' => 0,
                            ];

        FrontendModel::getSession()->set('basket', $basket_user['list']);
        return true;

    }

    public function delete(){}
    public function update(){}

}
?>