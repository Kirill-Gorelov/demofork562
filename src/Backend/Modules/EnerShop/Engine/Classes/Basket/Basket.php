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

        $basket_user = $this->get();
        $key = array_search($item['id'], array_column($basket_user['list'], 'id'));
        // TODO: костыль, не знаю что пока  делать с поиском 
        /*
        когда я ищу вот так array_search($item['id'], array_column($basket_user['list'], 'id'));, то искомый элемент находится на нулевом месте
        Но по факту в $basket_user['list'] нету нулевого элемента, там элементы могут начинаться 1 или 2 или 3
        потому что когда мы удаляем нулевой товар, индексы массива остаются
        Обнулять индексы после удаления элементы массива? пока думаю
        */
        /*
        $key = '';
        foreach ($basket_user['list'] as $k => $value) {
            if ($value['id'] == $item['id']) {
                $key = $k;
                break;
            }
        }
        */

        if(filter_var($key, FILTER_VALIDATE_FLOAT)!== false){ // потому что найденный элемент может быть на нулевом месте
            $basket_user['list'][$key]['quantity'] += $item['quantity'];
            $basket_user['list'][$key]['item_price'] = intval($basket_user['list'][$key]['quantity']) * intval($basket_user['list'][$key]['price']);
            FrontendModel::getSession()->set('basket', $basket_user['list']);
        }else{
            $this->add($item);
        }

        return true;
    }

    public function update(int $item){}

    public function delete( int $id){ //id элемента в массиве basket, то есть НЕ id товара
        $basket_user = $this->get();
        unset($basket_user['list'][$id]);
        FrontendModel::getSession()->set('basket', array_values($basket_user['list']));
        return true;
    }

    private function add($item){
        $element = new CElement;
        $this->element_id = $element->getById($item['id']);

        if (!$this->element_id) {
            return false;
        }

        $basket_user = $this->get();
        //TODO: решить вопрос со скидками и со свойствами товаров. Так же надо учитывать, что скидка может быть как в рублях,  так и в процентах
        $basket_user['list'][] = ['id' => $this->element_id['id'], 
                            'title' => $this->element_id['title'],
                            'image' => $this->element_id['image'],
                            'price' => $this->element_id['price'],
                            'quantity' => $item['quantity'],
                            'discount' => 0,
                            'item_price' => $this->element_id['price'] * $item['quantity'],
                            'discount_price' => 0,
                            ];

        FrontendModel::getSession()->set('basket', $basket_user['list']);
        return true;
    }

}
?>