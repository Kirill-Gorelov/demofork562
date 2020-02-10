<?php 
namespace Backend\Modules\EnerIblocks\Ajax;

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAjaxAction;
use Symfony\Component\HttpFoundation\Response;
use Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMeta;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

class UpdateSelectValue extends BackendBaseAjaxAction{

    public function execute():void
    {
        parent::execute();

        $element = $this->getRequest()->get('element');
        $data = $this->getRequest()->get('data');
        $data = array_chunk($data, 2);
        // print_r($data);
        $new_data = [];
        foreach ($data as $key => $value) {
            $new_data[] = ['xml_id'=>$element, 'key'=>$value['0']['value'], 'value'=>$value['1']['value']];
        }
        print_r($new_data);
        // var_export($data);
        // $rr = $this->get('doctrine')->getRepository(CategoryMeta::class)->getDefaultMetaValueForSelect($element);
        // $this->output(Response::HTTP_OK, ['response' => $rr], '');
        
    }
}

?>