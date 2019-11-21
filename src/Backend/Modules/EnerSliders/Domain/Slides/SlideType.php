<?php

namespace Backend\Modules\EnerSliders\Domain\Slides;

use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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

class SlideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('sort',
            IntegerType::class,
            [
                'label' => 'Сортировка',
                'empty_data' => false
            ]
        )->add('active',
            CheckboxType::class,
            [
                'label' => 'Активно',
                'required' => false,
            ]
        )->add('title',
            TextType::class,
            [
                'label' => 'Заголовок',
                'empty_data' => false
            ]
        )->add('link',
            TextType::class,
            [
                'label' => 'Ссылка',
                'empty_data' => false
            ]

        )->add('image',
            TextType::class,
            [
                'label' => 'Изображение',
                'required' => false,
                'attr' => ['class'=>'mediaselect'],
            ]
        )->add('description',
            TextareaType::class,
            [
                'label' => 'Подпись к слайду',
                'empty_data' => false
            ]
        );
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Slide::class,
        ));
    }

}