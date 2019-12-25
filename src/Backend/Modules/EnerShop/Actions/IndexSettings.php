<?php

namespace Backend\Modules\EnerShop\Actions;

use Symfony\Component\Form\Form;
use Backend\Modules\EnerShop\Domain\Settings\Setting;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Meta as BackendMeta;
use Backend\Modules\Pages\Engine\Model as BackendPagesModel;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;


class IndexSettings extends BackendBaseActionEdit {

    protected $settings;

    private function loadData()
    {
        $this->settings = Setting::getAll();

        $this->settings = array_combine(array_column($this->settings, 'key'), $this->settings);
        // var_export($this->settings);
    }

    private function loadForm(){
        $this->form = new BackendForm('edit');
        $this->form->addText('nds', $this->settings['nds']['value'], 255, 'form-control', 'form-control danger');
        $this->form->addText('prefix', $this->settings['prefix']['value'], 255, 'form-control', 'form-control danger');
        $this->form->addText('time_save_basket', $this->settings['time_save_basket']['value'], 255, 'form-control', 'form-control danger');
        $this->form->addText('change_status_for_pay', $this->settings['change_status_for_pay']['value'], 255, 'form-control', 'form-control danger');
        $this->form->addText('page_payment_success', $this->settings['page_payment_success']['value'], 255, 'form-control', 'form-control danger');
        $this->form->addText('page_payment_error', $this->settings['page_payment_error']['value'], 255, 'form-control', 'form-control danger');
        $this->form->addText('redirect_page_payment_success', $this->settings['redirect_page_payment_success']['value'], 255, 'form-control', 'form-control danger');
        $this->form->addText('redirect_page_payment_error', $this->settings['redirect_page_payment_error']['value'], 255, 'form-control', 'form-control danger');
    }



    public function execute(): void
    {
        parent::execute();
        $this->loadData();
        $this->loadForm();

        if ($this->form->isSubmitted()) {

            parent::parse();
            $this->display();
    
            $item = [
                'title' => $this->form->getField('title')->getValue(),
                'code' => $this->form->getField('code')->getValue(),
                'image' => $this->form->getField('image')->getValue(),
                'category' => $this->getRequest()->get('cat'),
                'sort' => $this->form->getField('sort')->getValue(),
                'active' => $this->form->getField('active')->getValue(),
                'description' => $this->form->getField('description')->getValue(),
                'text' => $this->form->getField('text')->getValue(),
            ];
            $this->get('doctrine')->getRepository(CategoryElement::class)->update($this->getRequest()->get('id'), $item);

            $this->redirect(BackendModel::createUrlForAction('category_element_index', null, null, ['cti'=> $this->getRequest()->get('cti'), 'cat'=> $this->getRequest()->get('cat')]));
            return;
        }
        parent::parse();
        $this->display();
    }

}
