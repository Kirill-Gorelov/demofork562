<?php

namespace Backend\Modules\EnerBanners\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Language\Locale;
use Backend\Modules\EnerBanners\Domain\Banners\BannerDataGrid;

/**
 * This is the index-action (default), it will display the overview
 */
class Index extends BackendBaseActionIndex
{
    public function execute(): void
    {
        parent::execute();
        // $this->template->assign('dataGrid', 'data');
        $this->template->assign('dataGrid', BannerDataGrid::getHtml(Locale::workingLocale()));
        $this->parse();
        $this->display();
    }
}
