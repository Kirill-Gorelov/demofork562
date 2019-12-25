<?php

namespace Backend\Modules\EnerShop\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Language\Locale;
use Backend\Modules\EnerShop\Domain\PayMethods\PayMethodDataGrid;

/**
 * This is the index-action (default), it will display the overview
 */
class PayIndex extends BackendBaseActionIndex
{
    public function execute(): void
    {
        parent::execute();
        // $this->template->assign('dataGrid', 'data');
        $this->template->assign('dataGrid', PayMethodDataGrid::getHtml(Locale::workingLocale()));
        $this->parse();
        $this->display();
    }
}
