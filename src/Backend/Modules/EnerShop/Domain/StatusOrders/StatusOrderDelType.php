<?php

namespace Backend\Modules\EnerShop\Domain\StatusOrders;

use Backend\Form\Type\DeleteType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

final class StatusOrderDelType extends DeleteType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $options['action'] = 'status_delete';
        parent::buildForm($builder, $options);

        $builder->add('id', HiddenType::class);
    }
}
