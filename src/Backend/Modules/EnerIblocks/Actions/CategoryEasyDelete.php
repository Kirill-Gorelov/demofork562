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


        // $id = $deleteForm->getData()['id'];
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


        // оставлю для демо пока
        /*
        $el = new CElement;
        $sort = ['id'=>'desc'];
        // $sort = ['id'=>'asc'];
        $filter = [];
        // $filter = ['active'=>true];
        // $filter = ['active'=>false];
        // $filter = ['title'=>'Пица без категории'];
        // $filter = ['title'=>'%тети'];
        // $filter = ['title'=>'%от%'];
        // $filter = ['code'=>'picca_bez_kategorii2'];
        $filter = ['category'=>'7'];
        // $filter = ['id'=>'28'];
        // $filter = ['id'=>array('28','36')]; // ?
        // $filter = ['id'=>'28, 36, 41']; // ?
        // $filter = ['id'=>'28', 'category'=>7];
        
        $rez = $el->getList($sort, $filter);
        // var_dump($rez);
        var_dump(array_column($rez, 'id'));
        // die;
        */
    }
}
