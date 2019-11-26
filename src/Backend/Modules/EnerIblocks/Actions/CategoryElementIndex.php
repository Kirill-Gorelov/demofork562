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
    private $categories;

    public function execute(): void
    {
        parent::execute();

        $this->loadDatagrid();
        $this->parse();
        $this->display();
    }

    private function loadDatagrid(): void
    {
        $this->category = $this->get('doctrine')->getRepository(Category::class)->getAllCategory();
        $this->elements = $this->get('doctrine')->getRepository(CategoryElement::class)->getAllElementsById(1);
        $this->dataGrid = new BackendDataGridArray($this->elements);

        $this->dataGrid->addColumn(
            'edit',
            null,
            Language::lbl('Edit'),
            BackendModel::createUrlForAction('EditToken') . '&amp;id=[id]',
            Language::lbl('Edit')
        );

        // $this->dataGrid->setColumnFunction([$this, 'formatCategory'], ['[category]'], 'category', true);
    }

    /**
     * Выполняет преобразование категориии
     * @param string $category_id
     * @return string
     */
    public function formatCategory(string $category_id): string
    {
        foreach ($this->categories as $key => $value) {
            if ($category_id == $value)
                return $key;
        }
        return $category_id;
    }

    protected function parse(): void
    {
        parent::parse();

        $this->template->assign('dataGrid', $this->dataGrid->getContent());
    }
}
