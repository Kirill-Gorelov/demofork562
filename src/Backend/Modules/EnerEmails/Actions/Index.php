<?php

namespace Backend\Modules\EnerEmails\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Language\Locale;
use Backend\Modules\EnerEmails\Domain\EnerEmail\EnerEmailDataGrid;

class Index extends BackendBaseActionIndex
{
    public function execute(): void
    {
        parent::execute();
        $this->template->assign('dataGrid', EnerEmailDataGrid::getHtml(Locale::workingLocale()));
        $this->parse();
        $this->display();
    }
}
