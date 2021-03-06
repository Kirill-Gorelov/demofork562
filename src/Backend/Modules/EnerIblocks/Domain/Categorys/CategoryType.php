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
use Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMetaType;


use Common\Form\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        )->add('sort',
            TextType::class,
            [
                'label' => 'сортировка',
                'empty_data' => false
            ]
        )->add('parent',
            HiddenType::class,
            [
                'label' => 'parent',
                'empty_data' => false,
                'data'=> isset($_GET['cat']) ? $_GET['cat'] :  0,
            ]
        )->add('category_type_id',
            HiddenType::class,
            [
                'data' => $_GET['cti'], //TODO:тоже бы как-то заменить
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
                // 'empty_data' => false,
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
        )->add('price_catalog',
        CheckboxType::class,
            [
                'label' => 'Торговый каталог',
                'required' => false,
            ]
        ); 

        if(!isset($_GET['cat']) or !isset($_GET['id'])){
            $builder->add('cmeta', CollectionType::class, [
                'entry_type' => CategoryMetaType::class,
                'by_reference' => false,
                //'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'allow_sequence' => false,
                'required' => false,
                'label' => 'Мета данные',
                //'profession' => $options['data'],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Category::class,
        ));
    }

}
