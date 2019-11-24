<?php

namespace Backend\Modules\EnerIblocks\Domain\Categorys;

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
class CategoryDataGrid extends DataGridDatabase
{
    public function __construct(Locale $locale)
    {

        //TODO: переделать на регуест
        //TODO: в таблицу добавить язык
        //просмотор списка инфоблоков
        if (isset($_GET['id'])) {
            parent::__construct(
                'SELECT i.id, i.title FROM category AS i WHERE parent = :parent',
                ['parent' => $_GET['id']]
            );
            $editUrl = Model::createUrlForAction('edit', null, null, ['id' => '[id]', 'iblock'=>15], false);
        }else{
            //просмотр список ТИПОВ инфоблоков
            parent::__construct(
                'SELECT i.id, i.title FROM category AS i WHERE parent = 0'
            );
            $editUrl = Model::createUrlForAction('index', null, null, ['id' => '[id]', 'iblock'=>15], false);
        }

        $this->setSortingColumns(['id']);
        $this->setSortParameter('ask');

        $this->setColumnFunction([TemplateModifiers::class, 'showBool'], ['[active]', false], 'isActive');

        // check if this action is allowed
        if (BackendAuthentication::isAllowedAction('Edit')) {
            
            if (!isset($_GET['id'])) {
                $viewUrl = Model::createUrlForAction('index', null, null, ['id' => '[id]'], false);
                $this->addColumn('index', null, Language::lbl('View'), $viewUrl, Language::lbl('View'));
            }
            $this->addColumn('edit', null, Language::lbl('Edit'), $editUrl, Language::lbl('Edit'));
        }
    }

    public static function getHtml(Locale $locale): string
    {
        return (new self($locale))->getContent();
    }
}
