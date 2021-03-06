<?php 
namespace Backend\Modules\EnerIblocks\Engine;

use Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMeta;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElement;
use Backend\Core\Engine\Model as BackendModel;

//FIXME: Сюда ничего не пишем, без моего ведома. 
// потому мои обновления могут затереть ваши обновления
class CElement extends BackendModel  {

    public function getById(int $id){
        // получаем сами мета, потому что они динамичные, то есть тут эталон меты которые всегда актуальные
        //TODO: как то тут у меня все не очень (((((
        // первый вариант с получением всех мет, даже если они еще не проставлены. Но тут требуется сделать два лишних запроса
        /*    
        $this->element = $this->get('doctrine')->getRepository(CategoryElement::class)->getElement($id);
        $this->cti = $this->get('doctrine')->getRepository(Category::class)->getCTId($this->element['category']);
        $this->meta = $this->get('doctrine')->getRepository(CategoryMeta::class)->getMetaByType($this->cti);
        $this->meta_value = $this->get('doctrine')->getRepository(CategoryMeta::class)->getElementMeta($id);
        */
        // var_dump($this->meta_value);
        // var_dump($this->meta);
        if (intval($id) == 0) {
            return false; //TODO: или выкинуть исключение??
        }

        //Метод второй, получает только те меты, которые есть у элемента
        $element = $this->get('doctrine')->getRepository(CategoryElement::class)->getElement($id);
        // var_export($element);
        if (!$element) {
            return ''; //TODO: надо подумать может вернуть не пустоту, а что нибудь еще?
        }

        $this->meta_value = $this->get('doctrine')->getRepository(CategoryMeta::class)->getElementMeta($element);
        $element['meta'] = '';
        if ($this->meta_value) {
            $prep_arr = array_flip(array_column($this->meta_value, 'key'));
            foreach ($this->meta_value as $key => $value) {
                $key = $prep_arr[$value['key']];
                $this->meta[$key]['value'] = $value['value'];
            }
    
            // $element['meta'] = array_combine(array_column($this->meta, 'code'), $this->meta); //для первого варианта
            $element['meta'] = array_combine(array_column($this->meta_value, 'key'), $this->meta_value); // для второго варианта
        }

        return $element;
        
    }

    public function getList($sort = [], $filter = [], $limit = ''){
        $this->elements = $this->get('doctrine')->getRepository(CategoryElement::class)->getList($sort, $filter, $limit);
        if($this->elements){
            $this->elemnts_id = array_column($this->elements, 'id');
    
            $this->meta_value = $this->get('doctrine')->getRepository(CategoryMeta::class)->getElementMeta($this->elements);
            $this->elemnts_id = array_flip($this->elemnts_id);
            // var_export($this->elements);
            // var_export($this->elemnts_id);
            // var_export($this->meta_value);
    
            //к элементам, которые есть, добаляю меты, которые у них имеются
            array_filter($this->meta_value, function($item){
                $this->elements[$this->elemnts_id[$item['eid']]]['meta'][$item['key']] = $item;
            });

        }

        return $this->elements;
    }

}
?>