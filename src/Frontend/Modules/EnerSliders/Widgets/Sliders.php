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
        $this->header->addCSS('/src/Frontend/Themes/Fork/Core/Layout/Css/slider.css', 'EnerSliders', false);
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
            if (!empty($this->slider)) {
                $this->tpl = '/EnerSliders/Layout/Widgets/'.$this->slider->tpl;
            }else{
                $this->tpl = '/EnerSliders/Layout/Widgets/default.html.twig';
            }
        }else{
            $this->slider = '';
            $this->tpl = '';
        }

        // var_dump($this->slider->tpl);
        // die;
    }
}
