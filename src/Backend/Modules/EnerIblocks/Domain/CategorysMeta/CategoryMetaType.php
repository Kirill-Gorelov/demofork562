<?php

namespace Backend\Modules\EnerIblocks\Domain\CategorysMeta;

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

class CategoryMetaType extends AbstractType
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
        )->add('required',
            CheckboxType::class,
            [
                'label' => 'Обязательное',
                // 'empty_data' => '',
                //'required' => false,
            ]
        )->add('value',
            TextType::class,
            [
                'label' => 'Значение по умолчанию',
                'empty_data' => false
            ]
        )->add('code',
            TextType::class,
            [
                'label' => 'Символьный код(только английский)',
                'empty_data' => false
            ]
        )->add('type',
            ChoiceType::class, [
            'choices' => $this->getType(),
            'by_reference' => false,
            'placeholder' => 'Выбрать тип',
            'required' => true,
            'label' => 'Тип',
            ]
        ); 
    }

    private function getType(){
        return ['Строка'=>'string', 
        'Число'=>'number', 
        'Чекбокс'=>'checkbox', 
        // 'Радиобокс'=>'radio',
        'Картинка'=>'image',
        'Textarea'=>'textarea',
        'Список'=>'select',
        // 'HTML редактор(В процессе)'=>'textarea',
    ];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CategoryMeta::class,
        ));
    }

}
