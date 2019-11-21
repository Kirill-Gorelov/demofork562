<?php
namespace Backend\Modules\EnerIblocks\Installer;

use Common\ModuleExtraType;
use Backend\Core\Engine\Model;
use Backend\Core\Installer\ModuleInstaller;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;

final class Installer extends ModuleInstaller
{
    public function install()
    {
        $this->addModule('EnerIblocks');

        // $this->importLocale(__DIR__ . '/Data/locale.xml');
        $this->configureBackendNavigation();
        $this->configureBackendRights();
        $this->makeSearchable($this->getModule());

        $this->configureFrontendExtras();
        $this->configureEntities();

    }


    private function configureBackendNavigation(): void
    {
        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $moduleId = $this->setNavigation($navigationModulesId, 'EnerIblocks');
        $this->setNavigation($moduleId, 'Category', 'ener_iblocks/index', ['ener_iblocks/add']); 
    }


    private function configureBackendRights(): void
    {
        $this->setModuleRights(1, 'EnerIblocks');
        $this->setActionRights(1, 'EnerIblocks', 'Category');
    }


    private function configureFrontendExtras(): void
    {
    }

    private function configureEntities(): void
    {
        Model::get('fork.entity.create_schema')->forEntityClass(Category::class);
    }


}
