<?php

namespace Frontend\Modules\EnerSliders\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Backend\Modules\EnerSliders\Domain\Sliders\Slider;

class Sliders extends FrontendBaseWidget
{
    private $tpl;
    private $slider;
    
    public function execute(): void
    {       
        parent::execute();
        $this->loadData();
        $this->loadTemplate($this->tpl);
        $this->parse();
    }

    public function parse(){
        $this->template->assign('slider', $this->slider);
    }

    public function loadData() {
        if(intval($this->data['gallery_id']) != 0){
            $this->slider = $this->get('doctrine')->getRepository(Slider::class)->getSlider($this->data['gallery_id']);
            // $this->tpl = '/EnerSliders/Layout/Widgets/'.$this->slider->tpl;
            $this->tpl = '/EnerSliders/Layout/Widgets/default.html.twig';
        }else{
            $this->slider = '';
            $this->tpl = '';
        }

        // var_dump($this->slider);
        // die;
    }
}
