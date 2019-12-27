<?php

namespace Backend\Modules\EnerShop\Domain\Orders;

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
class OrderDataGrid extends DataGridDatabase
{
    public function __construct(Locale $locale)
    {
        parent::__construct(
            'SELECT i.id, i.contact_email
             FROM shop_order AS i
             WHERE 1',
            []
        );

        $this->setSortingColumns(['id']);
        $this->setSortParameter('asc');

        // $this->addColumn('isActive', ucfirst(Language::lbl('VisibleOnSite')), '[active]');
        // $this->addColumn('Sort', ucfirst(Language::lbl('Sorting')), '[sort]');
        $this->setColumnFunction([TemplateModifiers::class, 'showBool'], ['[active]', false], 'isActive');

        // check if this action is allowed
        if (BackendAuthentication::isAllowedAction('Edit')) {
            $editUrl = Model::createUrlForAction('order_edit', null, null, ['id' => '[id]'], false);
            $this->setColumnURL('contact_email', $editUrl);
            $this->addColumn('edit', null, Language::lbl('Edit'), $editUrl, Language::lbl('Edit'));

        }
    }

    public static function getHtml(Locale $locale): string
    {
        return (new self($locale))->getContent();
    }
}
