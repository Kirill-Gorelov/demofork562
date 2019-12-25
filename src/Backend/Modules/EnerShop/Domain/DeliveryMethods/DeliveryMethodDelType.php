<?php

namespace Backend\Modules\EnerShop\Domain\DeliveryMethods;

use Backend\Form\Type\DeleteType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

final class DeliveryMethodDelType extends DeleteType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $options['action'] = 'delivery_dell';
        parent::buildForm($builder, $options);

        $builder->add('id', HiddenType::class);
    }
}
