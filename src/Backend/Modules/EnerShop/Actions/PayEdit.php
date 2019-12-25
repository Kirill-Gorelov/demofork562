<?php

namespace Backend\Modules\EnerShop\Actions;

use Symfony\Component\Form\Form;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EnerShop\Domain\PayMethods\PayMethod;
use Backend\Modules\EnerShop\Domain\PayMethods\PayMethodType;
use Backend\Modules\EnerShop\Domain\PayMethods\PayMethodDelType;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;

/*
 * Контроллер редактирования
 */
class PayEdit extends BackendBaseActionEdit {

    protected $id;
    private $PayMethod;

    private function loadPayMethod(): void
    {
        $this->PayMethod = $this->get('doctrine')->getRepository(PayMethod::class)->findOneById($this->id);
    }

    private function getForm(): Form
    {
        $form = $this->createForm(
            PayMethodType::class,
            $this->PayMethod
        );

        // var_dump($form);
        // die;

        $form->handleRequest($this->getRequest());

        return $form;
    }

    private function parseForm(Form $form): void
    {
        $this->template->assign('form', $form->createView());

        $this->parse();
        $this->display();
    }

    /*
     * Создаём форму для кнопки удаления. Специфика движка
     */
    private function loadDeleteForm(): void
    {
        $deleteForm = $this->createForm(
            PayMethodDelType::class,
            ['id' => $this->id],
            ['module' => $this->getModule()]
        );
        $this->template->assign('deleteForm', $deleteForm->createView());
    }

    protected function parse(): void
    {
        parent::parse();
        // $this->template->assign('mediaGroup', $this->PayMethod->getMediaGroup());
        // $this->template->assign('image', $this->PayMethod->getImage());
        $this->template->assign('id', $this->PayMethod->getId());
    }

    public function execute(): void
    {
        parent::execute();

        $this->id = $this->getRequest()->get('id');

        $this->loadPayMethod();

        $form = $this->getForm();
        // dump($form);
        // die;

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->loadDeleteForm();
            $this->parseForm($form);
            return;
        }

        $this->get('doctrine')->getRepository(PayMethod::class)->update();
        $this->redirect(BackendModel::createUrlForAction('Index'));
    }

}
