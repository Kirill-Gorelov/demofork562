<?php 
namespace Backend\Modules\EnerIblocks\Engine;

use Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMeta;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElement;
use Backend\Core\Engine\Model as BackendModel;

//FIXME: Сюда ничего не пишем, без моего ведома. 
// потому мои обновления могут затереть ваши обновления
class СElementMeta extends BackendModel  {

    public function getList($sort = [], $filter = [], $limit = ''){
        $arResult = [];
        $this->elements = $this->get('doctrine')->getRepository(CategoryElement::class)->getList($sort, $filter, $limit);
        
        if($this->elements){
            $this->elemnts_id = array_column($this->elements, 'id');
    
            $this->meta_value = $this->get('doctrine')->getRepository(CategoryMeta::class)->getElementMeta($this->elemnts_id);
            $this->elemnts_id = array_flip($this->elemnts_id);
    
            //к элементам, которые есть, добаляю меты, которые у них имеются
            array_filter($this->meta_value, function($item){
                $this->elements[$this->elemnts_id[$item['eid']]]['meta'][$item['key']] = $item;
            });
        }

        return $this->elements;
    }

}
?>