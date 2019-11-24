<?php
namespace Backend\Modules\EnerIblocks\Installer;

use Common\ModuleExtraType;
use Backend\Core\Engine\Model;
use Backend\Core\Installer\ModuleInstaller;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;
use Backend\Modules\EnerIblocks\Domain\CategorysType\CategoryType;
use Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMeta;

final class Installer extends ModuleInstaller
{
    public function install()
    {
        $this->addModule('EnerIblocks');

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
        $moduleId = $this->setNavigation($navigationModulesId, 'EnerIblocks');
        $this->setNavigation($moduleId, 'CategoryType', 'ener_iblocks/category_type_index', ['ener_iblocks/category_type_add', 'ener_iblocks/category_type_edit', 'ener_iblocks/category_add', 'ener_iblocks/category_edit']); 
    }


    private function configureBackendRights(): void
    {
        $this->setModuleRights(1, 'EnerIblocks');
        $this->setActionRights(1, 'EnerIblocks', 'Category');
        $this->setActionRights(1, 'EnerIblocks', 'CategoryType');
        $this->setActionRights(1, 'EnerIblocks', 'CategoryMeta');
    }


    private function configureFrontendExtras(): void
    {
    }

    private function configureEntities(): void
    {
        Model::get('fork.entity.create_schema')->forEntityClass(Category::class);
        Model::get('fork.entity.create_schema')->forEntityClass(CategoryType::class);
        Model::get('fork.entity.create_schema')->forEntityClass(CategoryMeta::class);
    }


}
