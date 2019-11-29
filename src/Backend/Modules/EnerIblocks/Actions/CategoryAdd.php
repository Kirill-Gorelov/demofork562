<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;
use Backend\Modules\EnerIblocks\Domain\Categorys\CategoryType;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;

class CategoryAdd extends BackendBaseActionAdd
{

    private function getForm(): Form
    {

        $this->header->addJS('translit.js', 'EnerIblocks', false); //TODO:для категорий не работает транслит
        $form = $this->createForm(
            CategoryType::class,
            new Category()
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
        $meta = $product->getCategoryMeta();
        if (!empty($meta)){
            foreach ($meta as $item) {
                // var_dump($slider);
                // die;
                $item->setCmeta($slider);
            }
        }
        $this->get('doctrine')->getRepository(Category::class)->add($product);

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
