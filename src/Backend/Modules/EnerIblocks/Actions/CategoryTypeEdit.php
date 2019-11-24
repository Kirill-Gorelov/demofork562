<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerIblocks\Domain\CategorysType\CategoryType;
use Backend\Modules\EnerIblocks\Domain\CategorysType\CategoryTType;
use Backend\Modules\EnerIblocks\Domain\CategorysType\CategoryTypeDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

class CategoryTypeEdit extends BackendBaseActionEdit {

    protected $id;
    private $product;

    private function loadProduct(): void
    {
        $this->product = $this->get('doctrine')->getRepository(CategoryType::class)->findOneById($this->id);
    }

    private function getForm(): Form
    {
        $form = $this->createForm(
            CategoryTType::class,
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
            CategoryTypeDelType::class,
            ['id' => $this->id],
            ['module' => $this->getModule()]
        );
        $this->template->assign('deleteForm', $deleteForm->createView());
    }

    protected function parse(): void
    {
        parent::parse();
        $this->template->assign('id', $this->getRequest()->get('id'));
    }

    public function execute(): void
    {
        parent::execute();

        $this->id = $this->getRequest()->get('id');

        $this->loadProduct();

        $form = $this->getForm();

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->loadDeleteForm();
            $this->parseForm($form);
            return;
        }

        $this->get('doctrine')->getRepository(CategoryType::class)->update();
        $this->redirect(BackendModel::createUrlForAction('CategoryTypeIndex'));
    }

}
