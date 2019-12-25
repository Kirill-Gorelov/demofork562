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
        // var_export($this->settings);
    }

    private function loadForm(){
        $this->form = new BackendForm('edit');
        // $this->form->addText('title', $this->element['title'], 255, 'form-control title', 'form-control danger title');
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
