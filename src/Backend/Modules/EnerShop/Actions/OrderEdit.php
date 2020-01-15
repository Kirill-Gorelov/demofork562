<?php

namespace Backend\Modules\EnerShop\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Modules\Pages\Engine\Model as BackendPagesModel;
use Backend\Modules\EnerShop\Domain\Orders\Order;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\User;

class OrderEdit extends BackendBaseActionEdit {

    protected $id;
    protected $element;

    private function loadData()
    {
        $this->element = $this->get('doctrine')->getRepository(Order::class)->getOrderById($this->id);
        // var_export($this->element);
        var_export($this->element['order_number']);
    }

    private function loadForm(){
        $this->form = new BackendForm('edit');
        $this->form->addText('order_number', $this->element['order_number'], 255, 'form-control order_number', 'form-control danger title');
    }

    public function execute(): void
    {
        parent::execute();
        $this->id = $this->getRequest()->get('id');
        $this->loadData();
        $this->loadForm();
        
        parent::parse();
        $this->display();
    }

}
