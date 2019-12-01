<?php

namespace Backend\Modules\EnerIblocks\Domain\CategorysType;

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
class CategoryTypeDataGrid extends DataGridDatabase
{
    public function __construct(Locale $locale)
    {

        //TODO: переделать на регуест
        //TODO: сделать кнопку назад
        //TODO: в таблицу добавить язык
        //просмотор списка инфоблоков
        if (isset($_GET['id'])) {
            parent::__construct(
                'SELECT i.id, i.title FROM category AS i WHERE category_type_id = :category_type_id and parent = 0',
                ['category_type_id' => $_GET['id']]
            );
            $editUrl = Model::createUrlForAction('category_edit', null, null, ['id' => '[id]', 'cti'=>$_GET['id']], false);
        }else{
            //просмотр списока ТИПОВ инфоблоков
            parent::__construct(
                'SELECT i.id, i.title FROM category_type AS i WHERE 1'
            );
            $editUrl = Model::createUrlForAction('category_type_edit', null, null, ['id' => '[id]'], false);
        }

        $this->setSortingColumns(['id']);
        $this->setSortParameter('ask');

        $this->setColumnFunction([TemplateModifiers::class, 'showBool'], ['[active]', false], 'isActive');

        if (BackendAuthentication::isAllowedAction('Edit')) {
            if (!isset($_GET['id'])) {
                $viewUrl = Model::createUrlForAction('category_type_index', null, null, ['id' => '[id]'], false);
                $this->addColumn('category_type_index', null, Language::lbl('View'), $viewUrl, Language::lbl('View'));
            }
            $this->addColumn('edit', null, Language::lbl('Edit'), $editUrl, Language::lbl('Edit'));
        }
    }

    public static function getHtml(Locale $locale): string
    {
        return (new self($locale))->getContent();
    }
}
