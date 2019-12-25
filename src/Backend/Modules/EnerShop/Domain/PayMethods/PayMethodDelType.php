<?php

namespace Backend\Modules\EnerShop\Domain\PayMethods;

use Backend\Form\Type\DeleteType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

final class PayMethodDelType extends DeleteType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $options['action'] = 'delete_pay';
        parent::buildForm($builder, $options);

        $builder->add('id', HiddenType::class);
    }
}
