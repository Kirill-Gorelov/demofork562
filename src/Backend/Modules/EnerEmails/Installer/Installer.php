<?php

namespace Backend\Modules\EnerEmails\Installer;

use Backend\Core\Engine\Model;
use Backend\Core\Installer\ModuleInstaller;
use Backend\Modules\EnerEmails\Domain\EnerEmail\EnerEmail;

/**
 * Installer for the content blocks module
 */
class Installer extends ModuleInstaller
{
    public function install(): void
    {
        $this->addModule('EnerEmails');
        $this->importLocale(__DIR__ . '/Data/locale.xml');
        $this->configureBackendNavigation();
        $this->configureBackendRights();
        $this->configureEntities();
    }

    private function configureBackendNavigation(): void
    {
        // Set navigation for "Modules"
        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $this->setNavigation(
            $navigationModulesId,
            'EnerEmails',
            'ener_emails/index',
            ['ener_emails/add', 'ener_emails/edit']
        );
    }

    private function configureBackendRights(): void
    {
        $this->setModuleRights(1, 'EnerEmails');

        $this->setActionRights(1, 'EnerEmails', 'Add');
        $this->setActionRights(1, 'EnerEmails', 'Delete');
        $this->setActionRights(1, 'EnerEmails', 'Edit');
        $this->setActionRights(1, 'EnerEmails', 'Index');
    }

    private function configureEntities(): void
    {
        Model::get('fork.entity.create_schema')->forEntityClass(EnerEmail::class);
    }
}