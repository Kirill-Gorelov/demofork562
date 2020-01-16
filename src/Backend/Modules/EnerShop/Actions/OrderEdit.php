<?php

namespace Backend\Modules\EnerShop\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Modules\Pages\Engine\Model as BackendPagesModel;
// use Backend\Modules\EnerShop\Domain\Orders\Order;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\User;
use Backend\Modules\EnerShop\Engine\Classes\Orders\Order;


class OrderEdit extends BackendBaseActionEdit {

    protected $id;
    protected $element;

    private function loadData()
    {
        $cls_order = new Order();
        $this->element = $cls_order->getOrderById($this->id);
        // $this->element = $this->get('doctrine')->getRepository(Order::class)->getOrderById($this->id);
        // var_export($this->element);
        var_export($this->element);
    }

    private function loadForm(){
        $this->form = new BackendForm('edit');
        // $this->form->addText('order_number', $this->element['order_number'], 255, 'form-control order_number', 'form-control danger title');
        // $this->form->addText('code', $this->element['code'], 255, 'form-control', 'form-control danger');
        // $this->form->addText('image', $this->element['image'], 'form-control ', 'form-control mediaselect');
        // $this->form->addText('sort', $this->element['sort'], 5, 'form-control', 'form-control danger');
        // $this->form->addCheckbox('active', $this->element['active']);
        // $this->form->addEditor('description', $this->element['description'], 'form-control', 'form-control danger');
        // $this->form->addEditor('text', $this->element['text'], 'form-control', 'form-control danger');
        // $this->form->addText('date', $this->element['date'], 'form-control disabled');
        // $this->form->addText('edited_on', $this->element['edited_on'], 'form-control disabled');

        // $user_edit = new User($this->element['editor_user_id']);
        // $user_create = new User($this->element['creator_user_id']);
        // $this->form->addText('creator_user_id', $user_create->getEmail(), 'form-control disabled');
        // $this->form->addText('editor_user_id', $user_edit->getEmail(), 'form-control disabled');

        // $this->form->addText('weight', $this->element['weight'], null,'form-control');
        // $this->form->addText('length', $this->element['length'], null,'form-control');
        // $this->form->addText('width', $this->element['width'], null,'form-control');
        // $this->form->addText('height', $this->element['height'], null,'form-control');
        // $this->form->addText('quantity', $this->element['quantity'], null,'form-control');
        // $this->form->addText('discount', $this->element['discount'], null,'form-control');
        // $this->form->addText('coefficient', $this->element['coefficient'], null,'form-control');
        // $this->form->addText('unit', $this->element['unit'], null,'form-control');
        // $this->form->addText('price', $this->element['price'], null,'form-control');
        // $this->form->addText('purchase_price', $this->element['purchase_price'], null,'form-control');
    }

    public function execute(): void
    {
        parent::execute();
        $this->id = $this->getRequest()->get('id');
        $this->loadData();
        $this->loadForm();

        $this->template->assign('order_number', $this->element['order_number']);
        $this->template->assign('order_id', $this->element['id']);
        $this->template->assign('order_date_create', $this->element['date']);
        $this->template->assign('order_status', $this->element['id_status']);

        $this->template->assign('user_fio', $this->element['user_fio']);
        $this->template->assign('user_email', $this->element['user_email']);
        $this->template->assign('user_phone', $this->element['user_phone']);

        $this->template->assign('price', $this->element['price']);
        $this->template->assign('price_delivery', $this->element['price_delivery']);
        
        if ($this->form->isSubmitted()) {

            // parent::parse();
            // $this->display();
    
            // $item = [
            //     'order_number' => $this->form->getField('order_number')->getValue(),
            //     // 'code' => $this->form->getField('code')->getValue(),
            //     // 'image' => $this->form->getField('image')->getValue(),
            //     // 'category' => $this->getRequest()->get('cat'),
            //     // 'sort' => $this->form->getField('sort')->getValue(),
            //     // 'active' => $this->form->getField('active')->getValue(),
            //     // 'description' => $this->form->getField('description')->getValue(),
            //     // 'text' => $this->form->getField('text')->getValue(),
            // ];
            // $this->get('doctrine')->getRepository(CategoryElement::class)->update($this->getRequest()->get('id'), $item);

            // $this->redirect(BackendModel::createUrlForAction('category_element_index', null, null, ['cti'=> $this->getRequest()->get('cti'), 'cat'=> $this->getRequest()->get('cat')]));
            return;
        }
        parent::parse();
        $this->display();
    }

}
