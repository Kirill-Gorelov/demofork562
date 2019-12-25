<?php

namespace Backend\Modules\EnerShop\Domain\PayMethods;

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
class PayMethodDataGrid extends DataGridDatabase
{
    public function __construct(Locale $locale)
    {
        parent::__construct(
            'SELECT i.id, i.active, i.title, i.sort
             FROM shop_method_pay AS i
             WHERE 1',
            []
        );

        $this->setSortingColumns(['id']);
        $this->setSortParameter('desc');

        $this->addColumn('isActive', ucfirst(Language::lbl('VisibleOnSite')), '[active]');
        // $this->addColumn('Sort', ucfirst(Language::lbl('Sorting')), '[sort]');
        $this->setColumnFunction([TemplateModifiers::class, 'showBool'], ['[active]', false], 'isActive');

        // check if this action is allowed
        if (BackendAuthentication::isAllowedAction('Edit')) {
            $editUrl = Model::createUrlForAction('pay_edit', null, null, ['id' => '[id]'], false);
            $this->setColumnURL('title', $editUrl);
            $this->addColumn('edit', null, Language::lbl('Edit'), $editUrl, Language::lbl('Edit'));

        }
    }

    public static function getHtml(Locale $locale): string
    {
        return (new self($locale))->getContent();
    }
}
