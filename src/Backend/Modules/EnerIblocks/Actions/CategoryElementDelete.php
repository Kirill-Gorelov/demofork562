<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElement;
use Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMeta;

class CategoryElementDelete extends BackendBaseActionDelete
{

    public function execute(): void
    {
        parent::execute();

        // $id = $deleteForm->getData()['id'];
        $id = $this->getRequest()->get('id');
        $cti = $this->getRequest()->get('cti');
        $cat = $this->getRequest()->get('cat');
        
        // var_dump($id);
        // die;

        // $category = $this->get('doctrine')->getRepository(Category::class)->findOneById($id);

        // $this->get('doctrine')->getRepository(Category::class)->delete($category);


        $product = $this->get('doctrine')->getRepository(CategoryElement::class)->findOneById($id);

        $this->get('doctrine')->getRepository(CategoryElement::class)->delete($product);
        $this->get('doctrine')->getRepository(CategoryMeta::class)->delete_meta($id);
        

        $this->redirect(BackendModel::createUrlForAction('CategoryElementIndex', null, null, ['cti'=>$cti, 'cat'=>$cat]));
    }
}
