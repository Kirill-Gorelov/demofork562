<?php

namespace Backend\Modules\EnerBanners\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Modules\EnerBanners\Domain\Banners\Banner;
use Backend\Modules\EnerBanners\Domain\Banners\BannerType;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;

/*
 * Контроллер добавления товара
 */

class Add extends BackendBaseActionAdd
{

    private function getForm(): Form
    {
        $form = $this->createForm(
            BannerType::class,
            new Banner()
        );

        $form->handleRequest($this->getRequest());

        return $form;
    }

    private function parseForm(Form $form): void
    {
        $this->template->assign('form', $form->createView());
        // $this->template->assign('mediaGroup', $form->getData()->getMediaGroup());

        $this->parse();
        $this->display();
    }

    private function createBanner(Form $form): bool
    {
        $Banner = $form->getData();
        // dump($Banner);
        // die;

        // $oneslide = $Banner->getOneslide();
        // if (!empty($oneslide)){
        //     foreach ($oneslide as $slide) {
        //         $slide->setBanner($Banner);
        //     }
        // }
        $this->get('doctrine')->getRepository(Banner::class)->add($Banner);

        return true;
    }

    public function execute(): void
    {
        parent::execute();

        $form = $this->getForm();

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->parseForm($form);
            return;
        }

        $this->createBanner($form);
        $this->redirect(BackendModel::createUrlForAction('Index'));
    }
}
