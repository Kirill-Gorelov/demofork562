<?php

namespace Backend\Modules\EnerSliders\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Language\Locale;
use Backend\Modules\EnerSliders\Domain\Sliders\SliderDataGrid;

/**
 * This is the index-action (default), it will display the overview
 */
class Index extends BackendBaseActionIndex
{
    public function execute(): void
    {
        parent::execute();
        // $this->template->assign('dataGrid', 'data');
        $this->template->assign('dataGrid', SliderDataGrid::getHtml(Locale::workingLocale()));
        $this->parse();
        $this->display();
    }
}
