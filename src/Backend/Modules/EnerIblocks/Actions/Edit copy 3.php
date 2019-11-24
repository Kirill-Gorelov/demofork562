<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;
use Backend\Modules\EnerIblocks\Domain\Categorys\CategoryType;
use Backend\Modules\EnerIblocks\Domain\Categorys\CategoryDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

class Edit extends BackendBaseActionEdit {

    protected $id;
    private $product;

    private function loadProduct(): void
    {
        // $this->product = $this->get('doctrine')->getRepository(Category::class)->findOneById($this->id);
        $this->product = $this->get('doctrine')->getRepository(Category::class)->getCategory($this->id);
    }

    private function prepareForm(){
        var_dump($this->product);

    } 

    private function loadTree(){

    }

    private function loadForm(){
        $this->form = new BackendForm('edit');
        // $this->form->addText('title', $this->product['title'], 512, 'form-control title', 'form-control danger title');
        // $this->form->addText('title2', $this->product['title'], 512, 'form-control title', 'form-control danger title');
        
        $this->loadTree();
        $this->prepareForm();
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

        $this->loadProduct();
        $this->loadForm();
        

        $obj_label = [];
        $obj_label['title'] = 'заголовок';
        // $obj_label['description'] = 'Описание';

        // dump((object) $obj_label);
        $this->template->assign('idw', $this->product['id']);
        $this->template->assign('obj_label', $obj_label);

        if ($this->form->isSubmitted()) {
            $this->loadDeleteForm();
            // $this->parseForm($form);

            parent::parse();
            $this->template->assign('id', $this->product['id']);
            $this->display();
            var_dump($this->getRequest()->get('username'));
    
            // $this->get('doctrine')->getRepository(Category::class)->update();
            $item = [
                // 'title' => $this->form->getField('title')->getValue()
                'title' => ''
            ];

            // dump($item);
            // die;
            // $this->get('doctrine')->getRepository(Category::class)->customsave($this->id, $item);
            // $this->redirect(BackendModel::createUrlForAction('Category'));
            // return;
        }
        parent::parse();
        $this->display();

        // $prices = $this->product->getPrices();
        // if (!empty($prices)){
        //     foreach ($prices as $price) {
        //         $price->setProduct($this->product);
        //     }
        // }

    }

}
