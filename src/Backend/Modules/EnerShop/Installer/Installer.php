<?php
namespace Backend\Modules\EnerShop\Installer;

use Common\ModuleExtraType;
use Backend\Core\Engine\Model;
use Backend\Core\Installer\ModuleInstaller;
use Backend\Modules\EnerShop\Domain\PayMethods\PayMethod;

final class Installer extends ModuleInstaller
{
    public function install()
    {
        $this->addModule('EnerShop');

        // $this->importLocale(__DIR__ . '/Data/locale.xml');
        $this->configureBackendNavigation();
        $this->configureBackendRights();
        $this->makeSearchable($this->getModule());

        $this->configureFrontendExtras();
        $this->configureEntities();
        $this->importSQL(__DIR__ . '/Data/install.sql'); //нужно выполнить в последнюю очередь
    }


    private function configureBackendNavigation(): void
    {
        $shop_module = $this->setNavigation(null, 'EnerShop', null, null, 1030);
        $this->setNavigation(
            $shop_module,
            'Settings',
            'ener_shop/index_settings',
            [],
            100
        );

        $this->setNavigation(
            $shop_module,
            'PayMethod',
            'ener_shop/pay_index',
            ['ener_shop/pay_edit', 'ener_shop/pay_add', 'ener_shop/pay_delete'],
            2
        );

    }


    private function configureBackendRights(): void
    {
        $this->setModuleRights(1, 'EnerShop');
        $this->setActionRights(1, 'EnerShop', 'Settings');
        $this->setActionRights(1, 'EnerShop', 'PayMethod');
    }


    private function configureFrontendExtras(): void
    {
    }

    private function configureEntities(): void
    {
        Model::get('fork.entity.create_schema')->forEntityClass(PayMethod::class);
        // Model::get('fork.entity.create_schema')->forEntityClass(Banner::class);
    }


}
