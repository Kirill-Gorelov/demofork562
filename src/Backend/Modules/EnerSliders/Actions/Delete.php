<?php

namespace Backend\Modules\EnerSliders\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerSliders\Domain\Sliders\Slider;
use Backend\Modules\EnerSliders\Domain\Sliders\SliderDelType;

// use Backend\Modules\EnerSliders\Domain\Sliders\Price;

/**
 * Контроллер удаления банеров
 */
class Delete extends BackendBaseActionDelete
{

    public function execute(): void
    {
        parent::execute();

        $deleteForm = $this->createForm(
            SliderDelType::class,
            null,
            ['module' => $this->getModule()]
        );

        $deleteForm->handleRequest($this->getRequest());
        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            $this->redirect(BackendModel::createUrlForAction('Index', null, null, ['error' => 'something-went-wrong']));

            return;
        }

        $id = $deleteForm->getData()['id'];

        $slider = $this->get('doctrine')->getRepository(Slider::class)->findOneById($id);

        $this->get('doctrine')->getRepository(Slider::class)->delete($slider);

        $this->redirect(BackendModel::createUrlForAction('Index'));
    }
}
