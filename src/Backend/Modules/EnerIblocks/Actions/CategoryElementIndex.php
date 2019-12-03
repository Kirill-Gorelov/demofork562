<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Language\Locale;
use Backend\Core\Language\Language;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Base\ActionIndex as BaseActionIndex;
use Backend\Core\Engine\DataGridArray as BackendDataGridArray;
// use Backend\Modules\EnerIblocks\Domain\Categorys\CategoryDataGrid;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElement;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElementDataGrid;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElementCategoryTypeDataGrid;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElementViewDataGrid;

/**
 * This is the index-action (default), it will display the overview
 */
class CategoryElementIndex extends BackendBaseActionIndex
{
    private $category;
    private $elements;

    public function execute(): void
    {
        parent::execute();
        
        
        
        parent::parse();
        
        if($this->getRequest()->get('cti') and $this->getRequest()->get('cat')){
            $this->category = $this->get('doctrine')->getRepository(Category::class)->getCategorysById($this->getRequest()->get('cat'));
            $this->elements = $this->get('doctrine')->getRepository(CategoryElement::class)->getAllElementsById($this->getRequest()->get('cat'));
            
            $this->template->assign('categorys', $this->category);
            $this->template->assign('elements', $this->elements);
            // TODO: не передавать в шаблон а получать параметры через твиг
            $this->template->assign('get_cti', $this->getRequest()->get('cti'));
            $this->template->assign('get_cat', $this->getRequest()->get('cat'));
            // $this->template->assign('get_ctm', $this->getRequest()->get('ctm'));
        }elseif($this->getRequest()->get('cti')){
            $this->template->assign('dataGrid', CategoryElementDataGrid::getHtml(Locale::workingLocale()));
        }else{
            $this->template->assign('dataGrid', CategoryElementCategoryTypeDataGrid::getHtml(Locale::workingLocale()));
           
        }

        $this->display();
    }

    private function loadDatagrid(): void
    {
        // var_dump($this->elements);
        $this->dataGrid = new BackendDataGridArray($this->category);

        $this->dataGrid->addColumn(
            'edit',
            null,
            Language::lbl('Edit'),
            BackendModel::createUrlForAction('category_element_index') . '&amp;cti=[id]',
            Language::lbl('Edit')
        );

        // $this->dataGrid->setColumnFunction([$this, 'formatCategory'], ['[category]'], 'category', true);
    }

}
