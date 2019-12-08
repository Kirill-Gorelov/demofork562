<?php

namespace Frontend\Modules\EnerIblocks\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;


class Module extends FrontendBaseBlock
{
    public $listworks;
    public $listamenties;
    public function execute(): void
    {
        parent::execute();
        var_dump($this->getRequest()->getRequestUri());
        var_dump($this->getRequest()->getUri());
        var_dump($this->getRequest()->getQueryString());
        var_dump($this->getRequest()->getUri());
        var_dump($this->getRequest()->getUri());
        var_dump($this->getRequest()->getUri());

        $this->loadTemplate(FRONTEND_MODULES_PATH.'/Asaf/Layout/Templates/AmentiesPageDetail.html.twig');;
        // $this->loadData();
        $this->parse();
    }

    public function parse(){
        // $this->template->assign('listcategory', $this->listcategory);
        $this->template->assign('listamenties', $this->listamenties);
    }

    public function loadData() {
        $this->listamenties = $this->get('doctrine')->getRepository(Amenties::class)->getamentdata($this->blogId);
    }
}
