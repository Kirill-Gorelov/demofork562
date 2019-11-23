<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Language\Locale;
use Backend\Modules\EnerIblocks\Domain\Categorys\CategoryDataGrid;

/**
 * This is the index-action (default), it will display the overview
 */
class Index extends BackendBaseActionIndex
{
    public function execute(): void
    {
        parent::execute();
        $this->template->assign('dataGrid', CategoryDataGrid::getHtml(Locale::workingLocale()));
        $this->parse();
        $this->display();
    }
}
