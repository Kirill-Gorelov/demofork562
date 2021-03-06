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
use Backend\Modules\EnerShop\Engine\Orders\Order;


class OrderEdit extends BackendBaseActionEdit {

    protected $id;
    protected $element;
    protected $cls_order;


    private function loadData()
    {
        // $cls_order = new Order();
        $this->element = $this->cls_order->getOrderById(intval($this->id));
        // var_export($this->element);
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

    }

    public function execute(): void
    {
        parent::execute();
        $this->cls_order = new Order();
        $this->id = $this->getRequest()->get('id');
        $this->loadData();
        $this->loadForm();

        // TODO: а если пользователь авторизован/не существует
        $this->template->assign('order_number', $this->element['order_number']);
        $this->template->assign('order_id', $this->element['id']);
        $this->template->assign('order_date_create', $this->element['date']);
        $this->template->assign('order_status', isset($this->element['status']['title']) ? $this->element['status']['title'] : 'не известно');

        $this->template->assign('user', $this->element['user']);

        $this->template->assign('price', intval($this->element['price']));
        $this->template->assign('price_total', intval($this->element['price']) + intval($this->element['price_delivery']));
        $this->template->assign('price_delivery', intval($this->element['price_delivery']));
        $this->template->assign('pay', $this->element['pay']);
        $this->template->assign('delivery', $this->element['delivery']);
        
        $this->template->assign('order_product', $this->element['product']);
        
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
