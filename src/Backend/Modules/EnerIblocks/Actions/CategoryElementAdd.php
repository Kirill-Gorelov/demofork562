<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;
use Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMeta;
use Backend\Modules\EnerIblocks\Domain\Categorys\CategoryType;
use Backend\Modules\EnerIblocks\Domain\Categorys\CategoryDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

class CategoryElementAdd extends BackendBaseActionEdit {

    protected $id;
    protected $meta;

    private function insertFileHead(){
        $this->header->addJS('myjs.js', 'EnerIblocks', false);
        $this->header->addJS('jquery.cookie.js', 'EnerIblocks', false);
        $this->header->addJS('jquery.treegrid.js', 'EnerIblocks', false);
    }

    private function loadMeta()
    {
        $this->meta = $this->get('doctrine')->getRepository(CategoryMeta::class)->getMetaByType($this->getRequest()->get('ctm'));
        // var_export($this->meta);
    }

    //отлавливаем данные меты из формы, при заполнении не всех полей, что бы не набирать их снова
    private function loadMetaWhithoutFromError(){
        // foreach ($this->meta as $key => $value) {
        //     var_dump($this->getRequest()->get($value['code']));
        // }
    }

    private function loadForm(){
        $this->form = new BackendForm('edit');
        $this->form->addText('title', null, 512, 'form-control title', 'form-control danger title');
    }

    private function getdMetaForm(){
        $meta_arr = [];
        $meta_type = array_column($this->meta, 'code');

        foreach ($meta_type as $key => $value) {
            $value_request = $this->getRequest()->get($value);
            if (isset($value_request)) { //TODO:сомнительное условие ....
                $meta_arr[$value] = $value_request;
            }
        }
        // var_export($meta_arr);
        return $meta_arr;
    }

    private function loadDeleteForm(): void
    {
        $deleteForm = $this->createForm(
            CategoryDelType::class,
            ['id' => $this->id],
            ['module' => $this->getModule()]
        );
        $this->template->assign('deleteForm', $deleteForm->createView());
    }

    public function execute(): void
    {
        parent::execute();
        $this->id = $this->getRequest()->get('id');
        $this->insertFileHead();
        $this->loadForm();
        $this->loadMeta();
        $this->loadMetaWhithoutFromError();

        $this->template->assign('meta', json_encode($this->meta));

        if ($this->form->isSubmitted()) {
            $this->loadDeleteForm();
            // $this->parseForm($form);

            parent::parse();
            // $this->template->assign('id', $this->product['id']);
            $this->display();
    
            // $this->get('doctrine')->getRepository(Category::class)->update();
            $item = [
                // 'title' => $this->form->getField('title')->getValue()
                'title' => ''
            ];
            $this->getdMetaForm();

            // dump($item);
            // die;
            //TODO:надо еще будет получить id элемента который сохранили, что бы с этим id сохранить мета значения
            // $this->get('doctrine')->getRepository(Category::class)->customsave($this->id, $item);
            // $this->redirect(BackendModel::createUrlForAction('Category'));
            // return;
        }
        parent::parse();
        $this->display();
    }

}
