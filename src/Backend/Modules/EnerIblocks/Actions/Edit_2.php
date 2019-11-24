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

    // private function parseForm(Form $form): void
    // {
    //     $this->template->assign('form', $form->createView());

    //     $this->parse();
    //     $this->display();
    // }

    private function preareForm(){
        var_dump($this->product);
        die;
    } 

    private function loadForm(){
        $this->form = new BackendForm('edit');
        $this->form->addText('title', $this->product->getTitle(), 512, 'form-control title', 'form-control danger title');
        $this->form->addText('title2', $this->product->getTitle(), 512, 'form-control title', 'form-control danger title');
        
        // $variable = ['title'];
        // $type_variable = ['title'=>'addText'];
        // foreach ($variable as $key => $value) {
        //     $this->form->$type_variable[$value]($value, $this->product->getTitle(), 512, 'form-control title', 'form-control danger title');
        // }

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

    // protected function parse(): void
    // {
    //     parent::parse();
    //     // $this->template->assign('mediaGroup', $this->product->getMediaGroup());
    //     // $this->template->assign('image', $this->product->getImage());
    //     $this->template->assign('id', $this->product->getId());
    // }

    public function execute(): void
    {
        parent::execute();
        // $this->header->addJS($_SERVER['DOCUMENT_ROOT'].'/src/Backend/Modules/EnerIblocks/js', 'Core', false);
        // $this->header->addJS($_SERVER['DOCUMENT_ROOT'].'/src/Backend/Modules/EnerIblocks/js', 'EnerIblocks', false);
        // $this->header->addJS('jsonform.js', 'EnerIblocks', false);
        // $this->header->addJS('jsonform-split.js', 'EnerIblocks', false);
        // $this->header->addJS('jsonform-defaults.js', 'EnerIblocks', false);
        
        // $this->header->addCSS('http://cdn.jsdelivr.net/npm/alpaca@1.5.27/dist/alpaca/bootstrap/alpaca.min.css', '', false);
        
        // $this->header->addJS('http://code.jquery.com/jquery-1.11.1.min.js', '', false);
        // $this->header->addJS('http://cdn.jsdelivr.net/npm/alpaca@1.5.27/dist/alpaca/bootstrap/alpaca.min.js', '', false);
        $this->header->addJS('myjs.js', 'EnerIblocks', false);
        
        // FRONTEND_FILES_PATH
        $this->id = $this->getRequest()->get('id');

        $this->loadProduct();
        // $this->loadForm();

        $obj_label = [];
        $obj_label['title'] = 'заголовок';
        // $obj_label['description'] = 'Описание';

        // dump((object) $obj_label);
        // $this->template->assign('idw', $this->product->getId());
        $this->template->assign('obj_label', $obj_label);

        if ($this->form->isSubmitted()) {
            $this->loadDeleteForm();
            // $this->parseForm($form);

            parent::parse();
            $this->template->assign('id', $this->product->getId());
            $this->display();
    
            // $this->get('doctrine')->getRepository(Category::class)->update();
            $item = [
                'title' => $this->form->getField('title')->getValue()
            ];

            // dump($item);
            // die;
            $this->get('doctrine')->getRepository(Category::class)->customsave($this->id, $item);
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
