<?php

namespace Backend\Modules\EnerIblocks\Domain\Categorys;

use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroupType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Valid;
use Backend\Form\Type\EditorType;
use Backend\Form\Type\MetaType;
use Backend\Core\Engine\Model;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Common\Form\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Backend\Modules\TechCourses\Forms\SpeakerType;

class CategoryType extends AbstractType
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
        )->add('path',
            TextType::class,
            [
                'label' => 'slug',
                'empty_data' => false
            ]
        )->add('parent',
            HiddenType::class,
            [
                'label' => 'parent',
                'empty_data' => false
            ]
        ); 


    // $builder->add('main_category', ChoiceType::class, [
    //     'choices' =>['Услуги'=>'amenties', 'Продукты'=>'product'],
    //     'by_reference' => false,
    //     'placeholder' => 'Выбрать главную категорию',
    //     'required' => false,
    //     'label' => 'Главная категория',
    // ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Category::class,
        ));
    }

}
