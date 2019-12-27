<?php

namespace Backend\Modules\EnerShop\Domain\Orders;

use Backend\Form\Type\DeleteType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

final class OrderDelType extends DeleteType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $options['action'] = 'order_delete';
        parent::buildForm($builder, $options);

        $builder->add('id', HiddenType::class);
    }
}
