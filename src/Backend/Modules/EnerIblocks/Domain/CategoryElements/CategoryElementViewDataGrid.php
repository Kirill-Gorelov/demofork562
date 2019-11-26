<?php

namespace Backend\Modules\EnerIblocks\Domain\CategoryElements;

use Backend\Core\Engine\DataGridDatabase;
use Backend\Core\Engine\TemplateModifiers;
use Backend\Core\Engine\DataGridFunctions as BackendDataGridFunctions;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Model;
use Backend\Core\Language\Language;
use Backend\Core\Language\Locale;

/**
 * @TODO replace with a doctrine implementation of the data grid
 */
class CategoryElementViewDataGrid extends DataGridDatabase
{
    public function __construct(Locale $locale)
    {

        //TODO: переделать на регуест
        //TODO: сделать кнопку назад
        //TODO: сделать перевод

        $cti = $_GET['cti'];
        $cat = $_GET['cat'];
        parent::__construct(
            'SELECT i.id, i.title FROM category_element AS i WHERE category = :id',
            ['id' => $cat]
        );

        $this->setSortingColumns(['id']);
        $this->setSortParameter('ask');
        $this->setColumnFunction([TemplateModifiers::class, 'showBool'], ['[active]', false], 'isActive');

        $viewUrl = Model::createUrlForAction('category_element_index', null, null, ['cti' => $cti, 'cat'=> '[id]'], false);
        $this->addColumn('index', null, Language::lbl('View'), $viewUrl, Language::lbl('View'));
    }

    public static function getHtml(Locale $locale): string
    {
        return (new self($locale))->getContent();
    }
}
