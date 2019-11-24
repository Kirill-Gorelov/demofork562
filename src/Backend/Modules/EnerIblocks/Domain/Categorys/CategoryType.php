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
        )->add('code',
            TextType::class,
            [
                'label' => 'Символьный код(только английский)',
                'empty_data' => false
            ]
        )->add('parent',
            HiddenType::class,
            [
                'label' => 'parent',
                'empty_data' => false
            ]
        )->add('description',
            EditorType::class,
            [
                'label' => 'Короткое описание категории',
                'empty_data' => false,
                'required' => false,
            ]
        )->add('active',
            CheckboxType::class,
            [
                'label' => 'Активно',
                'empty_data' => false,
                'required' => false,
            ]
        )->add('image',
            TextType::class,
            [
                'label' => 'Картинка категории',
                'empty_data' => false,
                'required' => false,
                'attr' => ['class'=>'mediaselect'],
            ]
        )->add('date',
            TextType::class,
            [
                'label' => 'Дата создания',
                'empty_data' => false,
                'disabled' => true
            ]
        )->add('edited_on',
            TextType::class,
            [
                'label' => 'Дата изменения',
                'empty_data' => false,
                'disabled' => true,
                'required' => false,
            ]
        )->add('creator_user_id',
            TextType::class,
            [
                'label' => 'Создал',
                'empty_data' => false,
                'disabled' => true,
                'required' => false,
            ]
        )->add('editor_user_id',
            TextType::class,
            [
                'label' => 'Изменил',
                'empty_data' => false,
                'disabled' => true,
                'required' => false,
            ]
        )->add('id',
            TextType::class,
            [
                'label' => 'ID категории',
                'empty_data' => false,
                'disabled' => true,
                'required' => false,
            ]
        ); 
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Category::class,
        ));
    }

}
