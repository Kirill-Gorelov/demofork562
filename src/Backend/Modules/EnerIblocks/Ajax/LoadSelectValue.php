<?php 
namespace Backend\Modules\EnerIblocks\Ajax;

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAjaxAction;
use Symfony\Component\HttpFoundation\Response;
use Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMeta;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;


class LoadSelectValue extends BackendBaseAjaxAction{

    public function execute():void
    {
        parent::execute();

        $element = $this->getRequest()->get('element');
        $rr = $this->get('doctrine')->getRepository(CategoryMeta::class)->getDefaultMetaValueForSelect($element);
        $this->output(Response::HTTP_OK, ['response' => $rr], '');
        
    }
}

?>