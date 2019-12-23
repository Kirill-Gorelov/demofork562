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

class CategoryElementAdd extends BackendBaseActionEdit {

    protected $id;
    protected $meta;
    protected $ctm_id;
    

    private function insertFileHead(){
        $this->header->addJS('myjs.js', 'EnerIblocks', false);
        $this->header->addJS('translit.js', 'EnerIblocks', false);
    }

    private function loadMeta()
    {
        $this->ctm_id = $this->get('doctrine')->getRepository(Category::class)->getMainParent($this->getRequest()->get('cat'));
        $this->meta = $this->get('doctrine')->getRepository(CategoryMeta::class)->getMetaByType($this->ctm_id['id']);
    }

    private function loadForm(){
        $this->form = new BackendForm('edit');
        $this->form->addText('title', null, 255, 'form-control title', 'form-control danger title');
        $this->form->addText('code', null, 255, 'form-control', 'form-control danger');
        $this->form->addText('image', null, 'form-control ', 'form-control mediaselect');
        $this->form->addText('sort', 500, 5, 'form-control', 'form-control danger');
        $this->form->addCheckbox('active', 0);
        $this->form->addEditor('description', null, 'form-control', 'form-control danger');
        $this->form->addEditor('text', null, 'form-control', 'form-control danger');
    }

    private function loadFormCatalogPrice(){
        $this->form->addText('weight', null, 'form-control disabled');
        $this->form->addText('length', null, 'form-control disabled');
        $this->form->addText('width', null, 'form-control disabled');
        $this->form->addText('height', null, 'form-control disabled');
        $this->form->addText('quantity', 0, 'form-control disabled');
        $this->form->addText('discount', 0, 'form-control disabled');
        $this->form->addText('coefficient', 1, 'form-control disabled');
        $this->form->addText('unit', null, 'form-control disabled');
        $this->form->addText('price', 0, 'form-control disabled');
        $this->form->addText('purchase_price', 0, 'form-control disabled');
    }

    private function getMetaForm($id){
        $meta_arr = [];
        $meta_type = array_column($this->meta, 'code');

        foreach ($meta_type as $key => $value) {
            $value_request = $this->getRequest()->get($value);
            if (isset($value_request)) { //TODO:сомнительное условие ....
                $meta_arr[] = array('eid' => $id, 'key' => $value, 'value' => $value_request);
            }
        }

        return $meta_arr;
    }

    public function execute(): void
    {
        parent::execute();
        $this->id = $this->getRequest()->get('id');
        $this->insertFileHead();
        $this->loadForm();
        $this->loadMeta();
        if ($this->ctm_id['price_catalog'] == 1) {
            $this->loadFormCatalogPrice();
        }

        // TODO: не передавать в шаблон а получать параметры через твиг
        $this->template->assign('get_cti', $this->getRequest()->get('cti'));
        $this->template->assign('get_ctm', $this->getRequest()->get('ctm'));
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
            $id = $this->get('doctrine')->getRepository(CategoryElement::class)->add($item);
            
            $meta_res = $this->getMetaForm($id);
            if (!empty($meta_res)) {
                $this->get('doctrine')->getRepository(CategoryMeta::class)->insert_meta($meta_res);
            }

            $this->redirect(BackendModel::createUrlForAction('category_element_index', null, null, ['cti'=> $this->getRequest()->get('cti'), 'cat'=> $this->getRequest()->get('cat')]));
            return;
        }
        parent::parse();
        $this->display();
    }

}
