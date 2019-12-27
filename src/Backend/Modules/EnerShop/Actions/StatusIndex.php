<?php

namespace Backend\Modules\EnerShop\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Language\Locale;
use Backend\Modules\EnerShop\Domain\StatusOrders\StatusOrderDataGrid;

/**
 * This is the index-action (default), it will display the overview
 */
class StatusIndex extends BackendBaseActionIndex
{
    public function execute(): void
    {
        parent::execute();
        // $this->template->assign('dataGrid', 'data');
        $this->template->assign('dataGrid', StatusOrderDataGrid::getHtml(Locale::workingLocale()));
        $this->parse();
        $this->display();
    }
}
