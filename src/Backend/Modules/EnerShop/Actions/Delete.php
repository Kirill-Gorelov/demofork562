<?php

namespace Backend\Modules\EnerBanners\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerBanners\Domain\Banners\Banner;
use Backend\Modules\EnerBanners\Domain\Banners\BannerDelType;

// use Backend\Modules\EnerBanners\Domain\Banners\Price;

/**
 * Контроллер удаления банеров
 */
class Delete extends BackendBaseActionDelete
{

    public function execute(): void
    {
        parent::execute();

        $deleteForm = $this->createForm(
            BannerDelType::class,
            null,
            ['module' => $this->getModule()]
        );

        $deleteForm->handleRequest($this->getRequest());
        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            $this->redirect(BackendModel::createUrlForAction('Index', null, null, ['error' => 'something-went-wrong']));

            return;
        }

        $id = $deleteForm->getData()['id'];

        $Banner = $this->get('doctrine')->getRepository(Banner::class)->findOneById($id);

        $this->get('doctrine')->getRepository(Banner::class)->delete($Banner);

        $this->redirect(BackendModel::createUrlForAction('Index'));
    }
}
