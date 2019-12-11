<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElement;
use Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMeta;
use Backend\Modules\EnerIblocks\Engine\CSection;
use Backend\Modules\EnerIblocks\Engine\CElement;

class CategoryEasyDelete extends BackendBaseActionDelete
{

    public function execute(): void
    {
        parent::execute();

        $id = $this->getRequest()->get('id');
        $cti = $this->getRequest()->get('cti');
        $cat = $this->getRequest()->get('cat');

        $section = new CSection;
        // var_dump($r);
        try {
            $r = $section->delete($id);
        } catch (\Exception $e) {
            echo $e->getMessage();
            return ;
        }

        $this->redirect(BackendModel::createUrlForAction('CategoryElementIndex', null, null, ['cti'=>$cti, 'cat'=>$cat]));
    }
}
