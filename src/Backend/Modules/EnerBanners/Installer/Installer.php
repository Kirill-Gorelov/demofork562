<?php
namespace Backend\Modules\EnerBanners\Installer;

use Common\ModuleExtraType;
use Backend\Core\Engine\Model;
use Backend\Core\Installer\ModuleInstaller;
use Backend\Modules\EnerBanners\Domain\Banners\Banner;

final class Installer extends ModuleInstaller
{
    public function install()
    {
        $this->addModule('EnerBanners');

        $this->importLocale(__DIR__ . '/Data/locale.xml');
        $this->configureBackendNavigation();
        $this->configureBackendRights();
        $this->makeSearchable($this->getModule());

        $this->configureFrontendExtras();
        $this->configureEntities();

    }


    private function configureBackendNavigation(): void
    {
        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $moduleBannerfId = $this->setNavigation($navigationModulesId, 'EnerBanners');
        $this->setNavigation($moduleBannerfId, 'Banners', 'ener_banners/index', ['ener_banners/add']); 
    }


    private function configureBackendRights(): void
    {
        $this->setModuleRights(1, 'EnerBanners');
        $this->setActionRights(1, 'EnerBanners', 'Banners');
    }


    private function configureFrontendExtras(): void
    {
    }

    private function configureEntities(): void
    {
        Model::get('fork.entity.create_schema')->forEntityClass(Banner::class);
    }


}
