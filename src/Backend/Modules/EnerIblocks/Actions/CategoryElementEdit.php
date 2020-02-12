<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Meta as BackendMeta;
use Backend\Modules\Pages\Engine\Model as BackendPagesModel;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;
use Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMeta;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElement;
use Backend\Modules\EnerIblocks\Domain\Categorys\CategoryType;
use Backend\Modules\EnerIblocks\Domain\Categorys\CategoryDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\User;

class CategoryElementEdit extends BackendBaseActionEdit {

    protected $id;
    protected $meta;
    protected $element;

    private function insertFileHead(){
        $this->header->addJS('myjs.js', 'EnerIblocks', false);
    }

    private function loadData()
    {
        $this->element = $this->get('doctrine')->getRepository(CategoryElement::class)->getElement($this->getRequest()->get('id'));
        $this->ctm_id = $this->get('doctrine')->getRepository(Category::class)->getMainParent($this->element['category']);
        $this->meta = $this->get('doctrine')->getRepository(CategoryMeta::class)->getMetaByType($this->ctm_id['id']);
        $this->meta_value = $this->get('doctrine')->getRepository(CategoryMeta::class)->getElementMeta($this->element);
        
        // var_export($this->meta);


        $prep_arr = array_flip(array_column($this->meta, 'code'));

        // var_export($this->element);
        if($this->meta){
            foreach ($this->meta_value as $value) {
                $key = $prep_arr[$value['key']];
                $this->meta[$key]['value'] = $value['value'];
            }

            $cti = $this->getRequest()->get('cti');
            foreach ($this->meta as $key => $value) {
                if($value['type'] != 'select'){continue;}
                // var_export($value['code']);
                $this->meta[$key]['list'] = $this->get('doctrine')->getRepository(CategoryMeta::class)->getDefaultMetaValueForSelect($cti, $value['code']);
            }

            // var_export($this->meta);
        }

    }

    private function loadForm(){
        $this->form = new BackendForm('edit');
        $this->form->addText('title', $this->element['title'], 255, 'form-control title', 'form-control danger title');
        $this->form->addText('code', $this->element['code'], 255, 'form-control', 'form-control danger');
        $this->form->addText('image', $this->element['image'], 'form-control ', 'form-control mediaselect');
        $this->form->addText('sort', $this->element['sort'], 5, 'form-control', 'form-control danger');
        $this->form->addCheckbox('active', $this->element['active']);
        $this->form->addEditor('description', $this->element['description'], 'form-control', 'form-control danger');
        $this->form->addEditor('text', $this->element['text'], 'form-control', 'form-control danger');
        $this->form->addText('date', $this->element['date'], 'form-control disabled');
        $this->form->addText('edited_on', $this->element['edited_on'], 'form-control disabled');

        $user_edit = new User($this->element['editor_user_id']);
        $user_create = new User($this->element['creator_user_id']);
        $this->form->addText('creator_user_id', $user_create->getEmail(), 'form-control disabled');
        $this->form->addText('editor_user_id', $user_edit->getEmail(), 'form-control disabled');
    }

    private function loadFormCatalogPrice(){
        $this->form->addText('weight', $this->element['weight'], null,'form-control');
        $this->form->addText('length', $this->element['length'], null,'form-control');
        $this->form->addText('width', $this->element['width'], null,'form-control');
        $this->form->addText('height', $this->element['height'], null,'form-control');
        $this->form->addText('quantity', $this->element['quantity'], null,'form-control');
        $this->form->addText('discount', $this->element['discount'], null,'form-control');
        $this->form->addText('coefficient', $this->element['coefficient'], null,'form-control');
        $this->form->addText('unit', $this->element['unit'], null,'form-control');
        $this->form->addText('price', $this->element['price'], null,'form-control');
        $this->form->addText('purchase_price', $this->element['purchase_price'], null,'form-control');
    }

    private function getMetaForm($id){
        $meta_arr = [];
        $meta_type = array_column($this->meta_value, 'key');
        // $meta_type = array_column($this->meta, 'code');
        // var_export($meta_type);
        // var_export($this->meta);

        //обновляем поля
        foreach ($meta_type as $key => $value) {
            $value_request = $this->getRequest()->get($value);
            // var_dump($value);
            // var_dump($value, $value_request);
            if (isset($value_request)) { //TODO:сомнительное условие ....
                $meta_arr['current'][] = array('id' => $this->meta_value[$key]['id'], 'eid' => $id, 'key' => $value, 'value' => $value_request);
            }else{
                $meta_arr['current'][] = array('id' => $this->meta_value[$key]['id'], 'eid' => $id, 'key' => $value, 'value' => '0');
            }
        }

        //создаем новые поля, если набор мет изменился
        $meta_type_to_add = array_column($this->meta, 'code');
        $result = array_diff($meta_type_to_add, $meta_type);

        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $value_request = $this->getRequest()->get($value);
                if (isset($value_request)) { //TODO:сомнительное условие ....
                    $meta_arr['new'][] = array('eid' => $id, 'key' => $value, 'value' => $value_request);
                }
            }
        }

        return $meta_arr;
    }

    public function execute(): void
    {
        parent::execute();
        $this->id = $this->getRequest()->get('id');
        $this->insertFileHead();
        $this->loadData();
        $this->loadForm();
        if ($this->ctm_id['price_catalog'] == 1) {
            $this->loadFormCatalogPrice();
        }
        

        // TODO: не передавать в шаблон а получать параметры через твиг
        $this->template->assign('get_cti', $this->getRequest()->get('cti'));
        $this->template->assign('get_cat', $this->getRequest()->get('cat'));
        $this->template->assign('price_catalog', $this->ctm_id['price_catalog'] == 1 ? true : false);

        $this->template->assign('meta', json_encode($this->meta));

        if ($this->form->isSubmitted()) {

            parent::parse();
            $this->display();
    
            $item = [
                'title' => $this->form->getField('title')->getValue(),
                'code' => $this->form->getField('code')->getValue(),
                'image' => $this->form->getField('image')->getValue(),
                'category' => $this->getRequest()->get('cat'),
                'sort' => $this->form->getField('sort')->getValue(),
                'active' => $this->form->getField('active')->getValue(),
                'description' => $this->form->getField('description')->getValue(),
                'text' => $this->form->getField('text')->getValue(),
            ];
            $this->get('doctrine')->getRepository(CategoryElement::class)->update($this->getRequest()->get('id'), $item);

            if ($this->ctm_id['price_catalog'] == 1 ) {
                $item_price = [
                    'eid' => $this->getRequest()->get('id'),
                    'weight' => $this->form->getField('weight')->getValue(),
                    'length' => $this->form->getField('length')->getValue(),
                    'width' => $this->form->getField('width')->getValue(),
                    'height' => $this->form->getField('height')->getValue(),
                    'quantity' => $this->form->getField('quantity')->getValue(),
                    'discount' => $this->form->getField('discount')->getValue(),
                    'coefficient' => $this->form->getField('coefficient')->getValue(),
                    'unit' => $this->form->getField('unit')->getValue(),
                    'price' => $this->form->getField('price')->getValue(),
                    'purchase_price' => $this->form->getField('purchase_price')->getValue(),
                ];
                // var_export($this->element);
                if(is_null($this->element['s_id'])){
                    $this->get('doctrine')->getRepository(CategoryElement::class)->insert_price($item_price);
                }else{
                    $this->get('doctrine')->getRepository(CategoryElement::class)->update_price($item_price);
                }
            }
            
            $meta_res = $this->getMetaForm($this->getRequest()->get('id'));
            // var_dump($meta_res['current']);
            if (!empty($meta_res['current'])) {  //обновляем текущие меты, которые есть
                $this->get('doctrine')->getRepository(CategoryMeta::class)->update_meta($meta_res['current']);
            }
            
            if (!empty($meta_res['new'])) { //Добавляем новые меты, если они появились позднее
                $this->get('doctrine')->getRepository(CategoryMeta::class)->insert_meta($meta_res['new']);
            }

            // $this->redirect(BackendModel::createUrlForAction('category_element_index', null, null, ['cti'=> $this->getRequest()->get('cti'), 'cat'=> $this->getRequest()->get('cat')]));
            return;
        }
        parent::parse();
        $this->display();
    }

}
