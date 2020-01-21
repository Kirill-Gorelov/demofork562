<?php

namespace Backend\Modules\EnerEmails\Domain\EnerEmail;

use Backend\Core\Engine\DataGridDatabase;
use Backend\Core\Engine\TemplateModifiers;
use Backend\Core\Engine\DataGridFunctions as BackendDataGridFunctions;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Model;
use Backend\Core\Language\Language;
use Backend\Core\Language\Locale;
use Backend\Modules\EnerEmails\Engine\Email;

/**
 * @TODO replace with a doctrine implementation of the data grid
 */
class EnerEmailDataGrid extends DataGridDatabase
{
    public function __construct(Locale $locale)
    {
        parent::__construct(
            'SELECT i.id, i.subject, i.efrom
             FROM email_template AS i
             WHERE 1'
        );

        $this->setSortingColumns(['id']);
        $this->setSortParameter('asc');


        // $this->addColumn('arr', ucfirst(Language::lbl('Array')), implode(",", Email::getEmailById('[id]')));
		
        if (BackendAuthentication::isAllowedAction('Edit')) {
            $editUrl = Model::createUrlForAction('Edit', null, null, ['id' => '[id]'], false);
            $this->setColumnURL('subject', $editUrl);
            $this->addColumn('edit', null, Language::lbl('Edit'), $editUrl, Language::lbl('Edit'));

        }
		
       /* $idarr = Email::get('id');
        $this->addColumn('Email', ucfirst(Language::lbl('Email')), $idarr);*/
    }

    public static function getHtml(Locale $locale): string
    {
        return (new self($locale))->getContent();
    }
}
