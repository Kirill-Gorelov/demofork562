<?php

namespace Frontend\Modules\EnerIblocks\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Backend\Modules\EnerIblocks\Engine\CElement;
use Backend\Modules\EnerIblocks\Engine\CSection;


class Module extends FrontendBaseBlock
{
    public $element_id;
    public $element_ids;
    public function execute(): void
    {
        parent::execute();
        // var_dump($this->getRequest()->getRequestUri());
        // var_dump($this->getRequest()->getUri());
        // var_dump($this->getRequest()->getQueryString());
        // var_dump($this->getRequest()->getUri());
        // var_dump($this->getRequest()->getUri());
        // var_dump($this->getRequest()->getUri());
        // var_dump(FRONTEND_MODULES_PATH);

        $this->loadTemplate(FRONTEND_MODULES_PATH.'/EnerIblocks/Layout/Templates/Module.html.twig');
        $this->loadData();
        $this->parse();
    }

    public function parse(){
        // $this->template->assign('listcategory', $this->listcategory);
        $this->template->assign('element_id', $this->element_id);
        $this->template->assign('element_ids', $this->element_ids);
    }

    public function loadData() {
        $element = new CElement;
        $this->element_id = $element->getById(28);//просто случайное число для примера
        // var_export($this->element_id);

        // $sort = ['id'=>'desc'];
        $sort = ['id'=>'asc'];
        $filter = [];
        // $filter = ['active'=>true];
        // $filter = ['active'=>false];
        // $filter = ['title'=>'Пица без категории'];
        // $filter = ['title'=>'%тети'];
        // $filter = ['title'=>'%от%'];
        // $filter = ['code'=>'picca_bez_kategorii2'];
        // $filter = ['category'=>'7'];
        // $filter = ['id'=>'28'];
        $filter = ['id'=>array('28','36')]; // ?
        // $filter = ['id'=>'28, 36, 41']; // ?
        // $filter = ['id'=>'28', 'category'=>7];
        
        $this->element_ids = $element->getList($sort, $filter);
    }
}
