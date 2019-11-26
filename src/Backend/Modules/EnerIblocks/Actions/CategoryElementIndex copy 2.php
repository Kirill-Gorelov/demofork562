<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Language\Locale;
// use Backend\Modules\EnerIblocks\Domain\Categorys\CategoryDataGrid;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElementDataGrid;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElementCategoryTypeDataGrid;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElementViewDataGrid;

/**
 * This is the index-action (default), it will display the overview
 */
class CategoryElementIndex extends BackendBaseActionIndex
{
    public function execute(): void
    {
        //SELECT * FROM `category` c JOIN category_type cti ON c.category_type_id = cti.id JOIN category_element ce ON ce.category = c.id WHERE category = 1
        parent::execute();

        if($this->getRequest()->get('cti') and $this->getRequest()->get('cat')){
            $this->template->assign('dataGrid', CategoryElementViewDataGrid::getHtml(Locale::workingLocale()));
        }elseif($this->getRequest()->get('cti')){
            $this->template->assign('dataGrid', CategoryElementDataGrid::getHtml(Locale::workingLocale()));
        }else{
            $this->template->assign('dataGrid', CategoryElementCategoryTypeDataGrid::getHtml(Locale::workingLocale()));
        }

        $this->parse();
        $this->display();
    }

}
