<?php

namespace Backend\Modules\EnerShop\Domain\StatusOrders;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Backend\Form\Type\EditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class StatusOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title',
            TextType::class,
            [
                'label' => 'Название',
                'empty_data' => false,
                'required' => true,
            ]
        )->add('description',
            EditorType::class,
            [
                'label' => 'Короткое описание',
                'empty_data' => false,
                'required' => false,
            ]
        )->add('code',
            TextType::class,
            [
                'label' => 'Символьный код',
                'required' => true,
            ]
        ); 

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => StatusOrder::class,
        ));
    }

}
