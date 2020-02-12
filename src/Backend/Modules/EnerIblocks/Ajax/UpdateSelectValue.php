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

        $cti = $this->getRequest()->get('cti');
        $element = $this->getRequest()->get('element');
        $data = $this->getRequest()->get('data');
        $data = array_chunk($data, 2);
        // print_r($data);
        $new_data = [];
        foreach ($data as $key => $value) {
            $new_data[] = ['xml_id'=>$element, 'cti'=>$cti, 'key'=>$value['0']['value'], 'value'=>$value['1']['value']];
        }
        // var_export($new_data);
        // $rr = $this->get('doctrine')->getRepository(CategoryMeta::class)->getDefaultMetaValueForSelect($element);
        // var_export($rr);

        // $result = array_diff_assoc($new_data, $rr);
        // print_r($result);
        $rr = $this->get('doctrine')->getRepository(CategoryMeta::class)->getDefaultMetaValueForSelect($cti, $element);

        // $this->output(Response::HTTP_OK, ['response' => $rr], '');
        foreach ($new_data as $key => $value) {
            $this->get('doctrine')->getRepository(CategoryMeta::class)->insertDefaultMetaValueForSelect($value);
        }
        $this->output(Response::HTTP_OK, ['response' => ''], '');
    }
}

?>