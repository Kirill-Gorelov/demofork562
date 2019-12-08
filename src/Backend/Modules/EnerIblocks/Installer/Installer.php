<?php
namespace Backend\Modules\EnerIblocks\Installer;

use Common\ModuleExtraType;
use Backend\Core\Engine\Model;
use Backend\Core\Installer\ModuleInstaller;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;
use Backend\Modules\EnerIblocks\Domain\CategorysType\CategoryType;
use Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMeta;
use Backend\Modules\EnerIblocks\Domain\CategoryElements\CategoryElement;

final class Installer extends ModuleInstaller
{
    public function install()
    {
        $this->addModule('EnerIblocks');

        $this->importLocale(__DIR__ . '/Data/locale.xml');
        $this->importSQL(__DIR__ . '/Data/install.sql');
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
        $this->setNavigation($moduleId, 'CategoryElement', 'ener_iblocks/category_element_index', ['ener_iblocks/category_element_add', 'ener_iblocks/category_element_edit', 'ener_iblocks/category_easy_add', 'ener_iblocks/category_easy_edit']); 
    }


    private function configureBackendRights(): void
    {
        $this->setModuleRights(1, 'EnerIblocks');
        $this->setActionRights(1, 'EnerIblocks', 'Category');
        $this->setActionRights(1, 'EnerIblocks', 'CategoryType');
        $this->setActionRights(1, 'EnerIblocks', 'CategoryMeta');
        $this->setActionRights(1, 'EnerIblocks', 'CategoryElement');
    }


    private function configureFrontendExtras(): void
    {
        $this->insertExtra($this->getModule(), ModuleExtraType::block(), 'EnerIblocksModule', 'Module');  
    }

    private function configureEntities(): void
    {
        Model::get('fork.entity.create_schema')->forEntityClass(Category::class);
        Model::get('fork.entity.create_schema')->forEntityClass(CategoryType::class);
        Model::get('fork.entity.create_schema')->forEntityClass(CategoryMeta::class);
        Model::get('fork.entity.create_schema')->forEntityClass(CategoryElement::class);
    }


}
