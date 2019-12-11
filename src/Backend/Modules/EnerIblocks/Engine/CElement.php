<?php 
namespace Backend\Modules\EnerIblocks\Engine;

use Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMeta;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElement;
use Backend\Core\Engine\Model as BackendModel;

class CElement extends BackendModel {

    // public function add(){

    // }

    // public function delete(){
        
    // }

    // public function update(){
        
    // }

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

        //Метод второй, получает только те меты, которые есть у элемента
        $this->element = $this->get('doctrine')->getRepository(CategoryElement::class)->getElement($id);
        if ($this->element) {
            $this->meta_value = $this->get('doctrine')->getRepository(CategoryMeta::class)->getElementMeta($id);
    
            $prep_arr = array_flip(array_column($this->meta_value, 'key'));
            foreach ($this->meta_value as $key => $value) {
                $key = $prep_arr[$value['key']];
                $this->meta[$key]['value'] = $value['value'];
            }
    
            // $this->element['meta'] = array_combine(array_column($this->meta, 'code'), $this->meta); //для первого варианта
            $this->element['meta'] = array_combine(array_column($this->meta_value, 'key'), $this->meta_value); // для второго варианта
    
            return $this->element;
        }
        return ''; //TODO: надо подумать может вернуть не пустоту, а что нибудь еще?
    }

    public function getList(){
        // $this->meta = $this->get('doctrine')->getRepository(CategoryMeta::class)->getMetaByType($this->getRequest()->get('cat'));
        // $this->meta_value = $this->get('doctrine')->getRepository(CategoryElement::class)->getElementMeta($this->getRequest()->get('id'));
        // $this->element = $this->get('doctrine')->getRepository(CategoryElement::class)->getElement($this->getRequest()->get('id'));
        
        // $prep_arr = array_flip(array_column($this->meta, 'code'));
        // foreach ($this->meta_value as $value) {
        //     $key = $prep_arr[$value['key']];
        //     $this->meta[$key]['value'] = $value['value'];
        // }

        // $this->element['meta'] = $this->meta;

        // return $this->element;
    }

}
?>