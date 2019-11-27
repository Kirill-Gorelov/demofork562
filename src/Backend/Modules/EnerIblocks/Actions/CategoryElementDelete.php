<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElement;

class CategoryElementDelete extends BackendBaseActionDelete
{

    public function execute(): void
    {
        parent::execute();

        // $deleteForm = $this->createForm(
        //     CategoryTypeDelType::class,
        //     null,
        //     ['module' => $this->getModule()]
        // );
        
        // // dump('456');
        // // die;
        // $deleteForm->handleRequest($this->getRequest());
        // if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
        //     $this->redirect(BackendModel::createUrlForAction('Index', null, null, ['error' => 'something-went-wrong']));
        //     return;
        // }

        // $id = $deleteForm->getData()['id'];
        $id = $this->getRequest()->get('id');
        $cti = $this->getRequest()->get('cti');
        $cat = $this->getRequest()->get('cat');

        $product = $this->get('doctrine')->getRepository(CategoryElement::class)->findOneById($id);

        $this->get('doctrine')->getRepository(CategoryElement::class)->delete($product);

        $this->redirect(BackendModel::createUrlForAction('CategoryElementIndex', null, null, ['cti'=>$cti, 'cat'=>$cat]));
    }
}
