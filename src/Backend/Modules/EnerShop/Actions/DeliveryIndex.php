<?php

namespace Backend\Modules\EnerShop\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Language\Locale;
use Backend\Modules\EnerShop\Domain\DeliveryMethods\DeliveryMethodDataGrid;

/**
 * This is the index-action (default), it will display the overview
 */
class DeliveryIndex extends BackendBaseActionIndex
{
    public function execute(): void
    {
        parent::execute();
        // $this->template->assign('dataGrid', 'data');
        $this->template->assign('dataGrid', DeliveryMethodDataGrid::getHtml(Locale::workingLocale()));
        $this->parse();
        $this->display();
    }
}
