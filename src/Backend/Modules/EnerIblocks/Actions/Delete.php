<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;
use Backend\Modules\EnerIblocks\Domain\Categorys\CategoryDelType;

class Delete extends BackendBaseActionDelete
{

    public function execute(): void
    {
        parent::execute();

        $deleteForm = $this->createForm(
            CategoryDelType::class,
            null,
            ['module' => $this->getModule()]
        );
        
        // dump('456');
        // die;
        $deleteForm->handleRequest($this->getRequest());
        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            $this->redirect(BackendModel::createUrlForAction('Index', null, null, ['error' => 'something-went-wrong']));
            return;
        }

        $id = $deleteForm->getData()['id'];
        

        $product = $this->get('doctrine')->getRepository(Category::class)->findOneById($id);

        $this->get('doctrine')->getRepository(Category::class)->delete($product);

        $this->redirect(BackendModel::createUrlForAction('Category'));
    }
}
