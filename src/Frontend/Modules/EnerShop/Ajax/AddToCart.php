<?php 
namespace Frontend\Modules\EnerShop\Ajax;

use Frontend\Core\Engine\Base\AjaxAction as FrontendBaseAjaxAction;
use Symfony\Component\HttpFoundation\Response;
use Backend\Modules\EnerShop\Engine\Classes\Basket\Basket;

class AddToCart extends FrontendBaseAjaxAction{
    
    public function execute():void
    {
        parent::execute();

        $product_id = intval($this->getRequest()->get('product_id'));
        $quantity = intval($this->getRequest()->get('quantity'));
        
        // echo json_encode(['status' => false, 'msg' => 'текст ошибки']);
        $basket = new Basket();
        var_export($basket->addToBasket());

        // $this->output(Response::HTTP_OK, ['msg'=>'Товар добавлен в корзину!']);	
    }
}

?>