<?php

namespace Backend\Modules\EnerSliders\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerSliders\Domain\Slides\Slide;
use Backend\Modules\EnerSliders\Domain\Slides\Slideype;
use Backend\Modules\EnerSliders\Domain\Slides\SlideelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

/*
 * Контроллер редактирования
 */
class SlideEdit extends BackendBaseActionEdit {

    protected $id;

    private $slider;

    private function loadSlider(): void
    {
        $this->slider = $this->get('doctrine')->getRepository(Slide::class)->findOneById($this->id);

    }

    private function getForm(): Form
    {
        $form = $this->createForm(
            SlideType::class,
            $this->slider
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
            SlideDelType::class,
            ['id' => $this->id],
            ['module' => $this->getModule()]
        );
        $this->template->assign('deleteForm', $deleteForm->createView());
    }

    protected function parse(): void
    {
        parent::parse();
        // $this->template->assign('mediaGroup', $this->Slider->getMediaGroup());
        // $this->template->assign('image', $this->Slider->getImage());
        $this->template->assign('id', $this->slider->getId());
    }

    public function execute(): void
    {
        parent::execute();

        $this->id = $this->getRequest()->get('id');

        $this->loadSlider();

        $form = $this->getForm();
        // dump($form);
        // die;

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->loadDeleteForm();
            $this->parseForm($form);
            return;
        }

        // $sliders = $this->slider->getSlide();
        // if (!empty($sliders)){
        //     foreach ($sliders as $slide) {
        //         // var_dump($slide);
        //         // die;
        //         $slide->setPagesliders($this->slider);
        //     }
        // }

        $this->get('doctrine')->getRepository(Slide::class)->update();
        $this->redirect(BackendModel::createUrlForAction('Index'));
    }

}
