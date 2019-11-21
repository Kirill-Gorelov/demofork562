<?php

namespace Backend\Modules\EnerSliders\Domain\Sliders;

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
class SliderDataGrid extends DataGridDatabase
{
    public function __construct(Locale $locale)
    {
        parent::__construct(
            'SELECT i.id, i.active, i.title
             FROM sliders AS i
             WHERE i.language = :language',
            ['language' => $locale]
        );

        $this->setSortingColumns(['id']);
        $this->setSortParameter('desc');

        $this->addColumn('isActive', ucfirst(Language::lbl('VisibleOnSite')), '[active]');
        $this->setColumnFunction([TemplateModifiers::class, 'showBool'], ['[active]', false], 'isActive');

        // check if this action is allowed
        if (BackendAuthentication::isAllowedAction('Edit')) {
            $editUrl = Model::createUrlForAction('edit', null, null, ['id' => '[id]'], false);
            $this->setColumnURL('title', $editUrl);
            $this->addColumn('edit', null, Language::lbl('Edit'), $editUrl, Language::lbl('Edit'));

            // $editUrl = Model::createUrlForAction('delete', null, null, ['id' => '[id]'], false);
            // $this->setColumnURL('title', $editUrl);
            // $this->addColumn('delete', null, Language::lbl('Delete'), $editUrl, Language::lbl('Delete'));
        }
    }

    public static function getHtml(Locale $locale): string
    {
        return (new self($locale))->getContent();
    }
}
