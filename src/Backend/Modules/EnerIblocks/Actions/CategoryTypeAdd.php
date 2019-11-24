<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Modules\EnerIblocks\Domain\CategorysType\CategoryType;
use Backend\Modules\EnerIblocks\Domain\CategorysType\CategoryTType;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;

class CategoryTypeAdd extends BackendBaseActionAdd
{

    private function getForm(): Form
    {
        $form = $this->createForm(
            CategoryTType::class,
            new CategoryType()
        );

        // dump($form);
        // die;
        $form->handleRequest($this->getRequest());

        return $form;
    }

    private function parseForm(Form $form): void
    {
        $this->template->assign('form', $form->createView());
        // $this->template->assign('mediaGroup', $form->getData()->getMediaGroup());

        $this->parse();
        $this->display();
    }

    private function createProduct(Form $form): bool
    {
        $product = $form->getData();
        // dump($product);
        // die;
        $this->get('doctrine')->getRepository(CategoryType::class)->add($product);

        return true;
    }

    public function execute(): void
    {
        parent::execute();

        $form = $this->getForm();

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->parseForm($form);
            return;
        }

        $this->createProduct($form);
        $this->redirect(BackendModel::createUrlForAction('CategoryTypeIndex'));
    }
}
