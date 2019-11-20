<?php

namespace Backend\Modules\Imscevents\Installer;

use Backend\Core\Installer\ModuleInstaller;
use Common\ModuleExtraType;

class Installer extends ModuleInstaller
{

    /*
     * Устанавливаем модуль
     */
    public function install()
    {

        $this->addModule('Imscevents');

        $this->makeSearchable('Imscevents');
        $this->importSQL(__DIR__ . '/Data/install.sql');
        $this->importLocale(__DIR__ .  '/Data/locale.xml', true);


        /*
        * Устанавливаем права
        */
        $this->setModuleRights(1, 'Imscevents');
        $this->setActionRights(1, $this->getModule(), 'Index');
        $this->setActionRights(1, $this->getModule(), 'Addevent');
        $this->setActionRights(1, $this->getModule(), 'Editevent');
        $this->setActionRights(1, $this->getModule(), 'Delevent');

        $this->insertExtra($this->getModule(), ModuleExtraType::widget(), 'Eventsmain', 'Main');
        $this->insertExtra($this->getModule(), ModuleExtraType::widget(), 'Eventsdetail', 'Detail');
        $this->insertExtra($this->getModule(), ModuleExtraType::widget(), 'Afisha', 'Afisha');
        $this->insertExtra($this->getModule(), ModuleExtraType::widget(), 'Afishadate', 'Afishadate');
        $this->insertExtra($this->getModule(), ModuleExtraType::widget(), 'Lists', 'Lists');
        $this->insertExtra($this->getModule(), ModuleExtraType::block(), 'Eventsdetail', 'Detail');
        $this->insertExtra($this->getModule(), ModuleExtraType::block(), 'Afisha', 'Afisha');
        $this->insertExtra($this->getModule(), ModuleExtraType::block(), 'Afishadate', 'Afishadate');
        $this->insertExtra($this->getModule(), ModuleExtraType::block(), 'Lists', 'Lists');

        /*
         * Устанавливаем навигацию по административной части
         */
        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $navigationOiiId = $this->setNavigation($navigationModulesId, $this->getModule());
        $this->setNavigation(
            $navigationOiiId,
            'eventslist',
            'imscevents/index',
            ['imscevents/addevent', 'imscevent/editevent', 'imscevent/delevent']
        );

        $this->configureSettings();
    }

    /**
     * Настройка разделов с параметрами модуля
     */
    private function configureSettings(): void
    {
        $this->setActionRights(1, $this->getModule(), 'Settings');
        $this->setActionRights(1, $this->getModule(), 'AddDnpp');
        $this->setActionRights(1, $this->getModule(), 'EditDnpp');
        $this->setActionRights(1, $this->getModule(), 'DeleteDnpp');
        $this->setActionRights(1, $this->getModule(), 'DnppImport');
        $this->setActionRights(1, $this->getModule(), 'IctImport');
        $this->setActionRights(1, $this->getModule(), 'Status');
        $this->setActionRights(1, $this->getModule(), 'DeleteDnppEvents');
        $this->setActionRights(1, $this->getModule(), 'DeleteIctEvents');

        $navigationSettingsId = $this->setNavigation(null, 'Settings');
        $navigationModulesId = $this->setNavigation($navigationSettingsId, 'Modules');
        $this->setNavigation($navigationModulesId, $this->getModule(), 'imscevents/settings',
            ['imscevents/add_dnpp', 'imscevents/edit_dnpp', 'imscevents/delete_dnpp']);
    }
}
