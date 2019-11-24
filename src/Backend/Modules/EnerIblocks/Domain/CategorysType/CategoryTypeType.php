<?php

namespace Backend\Modules\EnerIblocks\Domain\CategorysType;

use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Backend\Form\Type\EditorType;
use Backend\Core\Engine\Model;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title',
            TextType::class,
            [
                'label' => 'Заголовок',
                'empty_data' => false,
                // 'attr' => 
            ]
        )->add('code',
            TextType::class,
            [
                'label' => 'Символьный код',
                'empty_data' => false
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CategoryTypeType::class,
        ));
    }

}
