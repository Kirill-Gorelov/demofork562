<?php

namespace Backend\Modules\EnerShop\Actions;

use Symfony\Component\Form\Form;
use Backend\Modules\EnerShop\Domain\ShopStatictics\ShopStatictic;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Meta as BackendMeta;
use Backend\Modules\Pages\Engine\Model as BackendPagesModel;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;


class ShopStaticticsIndex extends BackendBaseActionEdit {

    protected $settings;

    private function loadData()
    {
        // $this->settings = ShopStatictic::getAll();
        $this->settings = array();

        // $this->settings = array_combine(array_column($this->settings, 'key'), $this->settings);
        // var_export($this->settings);
    }


    public function execute(): void
    {
        parent::execute();
        $this->loadData();

        parent::parse();
        $this->display();
    }

}
