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

    private function insertFileHead(){
        $this->header->addJS('myjs.js', 'EnerIblocks', false);
    }

    private function loadMeta()
    {
        $this->meta = $this->get('doctrine')->getRepository(CategoryMeta::class)->getMetaByType($this->getRequest()->get('cat'));
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
        $this->form->addText('title', null, 255, 'form-control title', 'form-control danger title');
        $this->form->addText('code', null, 255, 'form-control', 'form-control danger');
        $this->form->addText('image', null, 'form-control ', 'form-control mediaselect');
        $this->form->addText('sort', null, 5, 'form-control', 'form-control danger');
        $this->form->addCheckbox('active', 0);
        $this->form->addEditor('description', null, 'form-control', 'form-control danger');
        $this->form->addEditor('text', null, 'form-control', 'form-control danger');

        // $this->meta = new BackendMeta($this->form, null, 'title', true);

        // // set callback for generating an unique URL
        // $this->meta->setUrlCallback(
        //     BackendPagesModel::class,
        //     'getUrl',
        //     [0, $this->getRequest()->query->getInt('parent'), false]
        // );
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

        // TODO: не передавать в шаблон а получать параметры через твиг
        $this->template->assign('get_cti', $this->getRequest()->get('cti'));
        $this->template->assign('get_cat', $this->getRequest()->get('cat'));

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
                'title' => $this->form->getField('title')->getValue(),
                'code' => $this->form->getField('code')->getValue(),
                'image' => $this->form->getField('image')->getValue(),
                'category' => $this->getRequest()->get('cat'),
                'sort' => $this->form->getField('sort')->getValue(),
                'active' => $this->form->getField('active')->getValue(),
                'description' => $this->form->getField('description')->getValue(),
                'text' => $this->form->getField('text')->getValue(),
            ];
            $id = $this->get('doctrine')->getRepository(CategoryElement::class)->insert($item);
            // var_dump($id);
            // var_dump($item);
            // die;
            // $this->get('doctrine')->getRepository(CategoryElement::class)->add((object) $item);

            // $this->getdMetaForm();

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
