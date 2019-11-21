<?php

namespace Backend\Modules\EnerBanners\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerBanners\Domain\Banners\Banner;
use Backend\Modules\EnerBanners\Domain\Banners\BannerType;
use Backend\Modules\EnerBanners\Domain\Banners\BannerDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

/*
 * Контроллер редактирования
 */
class Edit extends BackendBaseActionEdit {

    protected $id;

    private $banner;

    private function loadBanner(): void
    {
        $this->banner = $this->get('doctrine')->getRepository(Banner::class)->findOneById($this->id);

    }

    private function getForm(): Form
    {
        $form = $this->createForm(
            BannerType::class,
            $this->banner
        );

        // var_dump($form);
        // die;

        $form->handleRequest($this->getRequest());

        return $form;
    }

    private function parseForm(Form $form): void
    {
        $this->template->assign('form', $form->createView());

        $this->parse();
        $this->display();
    }

    /*
     * Создаём форму для кнопки удаления. Специфика движка
     */
    private function loadDeleteForm(): void
    {
        $deleteForm = $this->createForm(
            BannerDelType::class,
            ['id' => $this->id],
            ['module' => $this->getModule()]
        );
        $this->template->assign('deleteForm', $deleteForm->createView());
    }

    protected function parse(): void
    {
        parent::parse();
        // $this->template->assign('mediaGroup', $this->Banner->getMediaGroup());
        // $this->template->assign('image', $this->Banner->getImage());
        $this->template->assign('id', $this->banner->getId());
    }

    public function execute(): void
    {
        parent::execute();

        $this->id = $this->getRequest()->get('id');

        $this->loadBanner();

        $form = $this->getForm();
        // dump($form);
        // die;

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->loadDeleteForm();
            $this->parseForm($form);
            return;
        }

        // $sliders = $this->product->getOneslidep();
        // // dump($sliders);
        // // die;
        // if (!empty($sliders)){
        //     foreach ($sliders as $slide) {
        //         $slide->setProduct($this->product);
        //     }
        // }

        $this->get('doctrine')->getRepository(Banner::class)->update();
        $this->redirect(BackendModel::createUrlForAction('Index'));
    }

}
