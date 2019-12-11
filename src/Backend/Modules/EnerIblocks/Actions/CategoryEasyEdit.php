<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;
use Backend\Modules\EnerIblocks\Domain\Categorys\CategoryType;
use Backend\Modules\EnerIblocks\Domain\Categorys\CategoryDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

class CategoryEasyEdit extends BackendBaseActionEdit {

    protected $id;
    private $product;

    private function loadProduct(): void
    {
        $this->product = $this->get('doctrine')->getRepository(Category::class)->findOneById($this->id);
    }

    private function getForm(): Form
    {
        $form = $this->createForm(
            CategoryType::class,
            $this->product
        );

        $form->handleRequest($this->getRequest());

        return $form;
    }

    private function parseForm(Form $form): void
    {
        $this->template->assign('form', $form->createView());

        $this->parse();
        $this->display();
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
        $this->template->assign('id', $this->product->getId());
    }

    public function execute(): void
    {
        parent::execute();

        $this->id = $this->getRequest()->get('id');

        $this->loadProduct();

        $form = $this->getForm();
        // dump($form);
        // die;

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->loadDeleteForm();
            $this->parseForm($form);
            return;
        }

        // $meta = $this->product->getCmeta();
        // if (!empty($meta)){
        //     foreach ($meta as $item) {
        //         // var_dump($item);
        //         // die;
        //         $item->setCategoryMeta($this->product);
        //     }
        // }

        $this->get('doctrine')->getRepository(Category::class)->update();
        $this->redirect(BackendModel::createUrlForAction('category_element_index', null, null, ['id'=> $this->getRequest()->get('cti')]));

    }

}
