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
 * TODO: Отвечает за вывод типов категорий(они же инфоблоки)
 */
class CategoryElementCategoryTypeDataGrid extends DataGridDatabase
{
    public function __construct(Locale $locale)
    {

        parent::__construct(
            'SELECT i.id, i.title FROM category_type AS i WHERE 1  and language = :language',
            ['language'=>$locale]
        );

        $this->setSortingColumns(['id']);
        $this->setSortParameter('ask');
        $this->setColumnFunction([TemplateModifiers::class, 'showBool'], ['[active]', false], 'isActive');

        $viewUrl = Model::createUrlForAction('category_element_index', null, null, ['cti' => '[id]'], false);
        $this->addColumn('index', null, Language::lbl('View'), $viewUrl, Language::lbl('View'));
    }

    public static function getHtml(Locale $locale): string
    {
        return (new self($locale))->getContent();
    }
}
