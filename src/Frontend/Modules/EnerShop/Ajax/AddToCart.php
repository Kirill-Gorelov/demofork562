<?php 
namespace Frontend\Modules\EnerShop\Ajax;

use Frontend\Core\Engine\Base\AjaxAction as FrontendBaseAjaxAction;
use Symfony\Component\HttpFoundation\Response;
use Backend\Modules\EnerShop\Engine\Baskets\Basket;

class AddToCart extends FrontendBaseAjaxAction{
    
    public function execute():void
    {
        parent::execute();

        $product_id = intval($this->getRequest()->get('product_id'));
        $quantity = intval($this->getRequest()->get('quantity'));
        $product = ['id'=>$product_id, 'quantity' => $quantity];

        //надо подумать как передавать свойства товаров
        
        // echo json_encode(['status' => false, 'msg' => 'текст ошибки']);
        $basket = new Basket();
        var_export($basket->addToBasket($product));

        // $this->output(Response::HTTP_OK, ['msg'=>'Товар добавлен в корзину!']);	
    }
}

?>