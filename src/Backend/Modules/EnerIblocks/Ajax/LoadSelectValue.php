<?php 
namespace Backend\Modules\EnerIblocks\Ajax;

use Frontend\Core\Engine\Base\AjaxAction as FrontendBaseAjaxAction;
use Symfony\Component\HttpFoundation\Response;

class LoadSelectValue extends FrontendBaseAjaxAction{

    public function execute():void
    {
        parent::execute();

        $delivery_id = intval($this->getRequest()->get('delivery_id'));
        $this->output(Response::HTTP_OK, [], 'Подтвердите согласие на обработку персональных данных');
        return;


    }
}

?>