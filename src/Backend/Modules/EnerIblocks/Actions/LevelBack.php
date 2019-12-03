<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;

class LevelBack extends BackendBaseActionDelete
{
    public function execute(): void
    {
        parent::execute();

        $id_cat = $this->get('doctrine')->getRepository(Category::class)->getCategory($this->getRequest()->get('cat'));
        $id_cat = $id_cat['parent'];
        $cti = $this->getRequest()->get('cti');
        $this->redirect(BackendModel::createUrlForAction('category_element_index', null, null, ['cat' => $id_cat, 'cti'=>$cti]));

    }
}
