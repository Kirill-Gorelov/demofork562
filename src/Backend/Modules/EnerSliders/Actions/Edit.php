<?php

namespace Backend\Modules\EnerSliders\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerSliders\Domain\Sliders\Slider;
use Backend\Modules\EnerSliders\Domain\Sliders\SliderType;
use Backend\Modules\EnerSliders\Domain\Sliders\SliderDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

/*
 * Контроллер редактирования
 */
class Edit extends BackendBaseActionEdit {

    protected $id;

    private $slider;

    private function loadSlider(): void
    {
        $this->slider = $this->get('doctrine')->getRepository(Slider::class)->findOneById($this->id);
        // var_dump($this->slider);
        // die;
    }

    private function getForm(): Form
    {
        $form = $this->createForm(
            SliderType::class,
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
            SliderDelType::class,
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

        $sliders = $this->slider->getSlide();
        if (!empty($sliders)){
            foreach ($sliders as $slide) {
                // var_dump($slide);
                // die;
                $slide->setPagesliders($this->slider);
            }
        }

        $this->get('doctrine')->getRepository(Slider::class)->update();
        $this->redirect(BackendModel::createUrlForAction('Index'));
    }

}
