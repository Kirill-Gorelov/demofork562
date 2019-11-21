<?php

namespace Backend\Modules\EnerSliders\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Modules\EnerSliders\Domain\Sliders\Slider;
use Backend\Modules\EnerSliders\Domain\Sliders\SliderType;

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
            SliderType::class,
            new Slider()
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

    private function createSlider(Form $form): bool
    {
        $slider = $form->getData();
        // dump($Slider);
        // die;

        $oneslide = $slider->getSlide();
        if (!empty($oneslide)){
            foreach ($oneslide as $slide) {
                // var_dump($slider);
                // die;
                $slide->setPagesliders($slider);
            }
        }

        // var_dump($slider);
        // die;
        $this->get('doctrine')->getRepository(Slider::class)->add($slider);

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

        $this->createSlider($form);
        $this->redirect(BackendModel::createUrlForAction('Index'));
    }
}
