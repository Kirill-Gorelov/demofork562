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
        $this->product = $this->get('doctrine')->getRepository(Category::class)->findOneById($this->id);
        // $this->product = $this->get('doctrine')->getRepository(Category::class)->getCategory($this->id);
    }

    private function parseForm(Form $form): void
    {
        $this->template->assign('form', $form->createView());

        $this->parse();
        $this->display();
    }

    private function loadForm(){
        $this->form = new BackendForm('edit');
        // dump($this->product->getTitle());
        // die;
        $this->form->addText('title', $this->product->getTitle(), 512, 'form-control title', 'form-control danger title');

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

    protected function parse(): void
    {
        parent::parse();
        // $this->template->assign('mediaGroup', $this->product->getMediaGroup());
        // $this->template->assign('image', $this->product->getImage());
        $this->template->assign('id', $this->product->getId());
    }

    public function execute(): void
    {
        parent::execute();

        $this->id = $this->getRequest()->get('id');

        $this->loadProduct();
        $this->loadForm();

        if (!$this->form->isSubmitted()) {
            $this->loadDeleteForm();
            // $this->parseForm($form);

            parent::parse();
            $this->template->assign('id', $this->product->getId());
            $this->display();
    
            // $this->get('doctrine')->getRepository(Category::class)->update();
            // $item = [
            //     'title' => $this->form->getField('title')->getValue()
            // ];

            // $this->get('doctrine')->getRepository(Category::class)->customsave($this->id, $item);
            $this->redirect(BackendModel::createUrlForAction('Category'));
            return;
        }

        // $prices = $this->product->getPrices();
        // if (!empty($prices)){
        //     foreach ($prices as $price) {
        //         $price->setProduct($this->product);
        //     }
        // }

    }

}
