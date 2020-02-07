<?php 
namespace Backend\Modules\EnerIblocks\Ajax;

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAjaxAction;
use Symfony\Component\HttpFoundation\Response;

class LoadSelectValue extends BackendBaseAjaxAction{

    public function execute():void
    {
        parent::execute();

        $element = $this->getRequest()->get('element');
        $this->output(Response::HTTP_OK, [], 'Подтвердите согласие на обработку персональных данных'.$element);
        return;
    }
}

?>