<?php 
namespace Backend\Modules\EnerIblocks\Engine;

use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMeta;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElement;
use Backend\Modules\EnerIblocks\Engine\CElement;

//TODO:Я вот думаю, добавитьсюда интерфейс??
class CSection extends BackendModel  {

    // public function add(){

    // }

    public function delete(int $id){
        
        if(intval($id) == 0){
            throw new \Exception('Category not zero');
        }

        $category = $this->get('doctrine')->getRepository(Category::class)->findOneById($id);

        if($category){
            $this->get('doctrine')->getRepository(Category::class)->delete($category);
    
            $el = new CElement;
            $sort = [];
            $filter = ['category'=>$id];
            
            $rez = $el->getList($sort, $filter);
    
            if($rez){
                foreach (array_column($rez, 'id') as $key => $element_id) {
                    $product = $this->get('doctrine')->getRepository(CategoryElement::class)->findOneById($element_id);
    
                    $this->get('doctrine')->getRepository(CategoryElement::class)->delete($product);
                    $this->get('doctrine')->getRepository(CategoryMeta::class)->delete_meta($element_id);
                }
            }
        }
        
        return true;
    }

    // public function update(){
        
    // }

    // public function get(){
        
    // }

    // public function getList(){

    // }

}
?>