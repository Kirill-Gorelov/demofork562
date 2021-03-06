<?php

namespace Backend\Modules\EnerSliders\Domain\Sliders;

use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\LabelType;
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
use Backend\Modules\EnerSliders\Domain\Slides\SlideType;

use Common\Form\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Backend\Modules\TechCourses\Forms\SpeakerType;

class SliderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('active',
            CheckboxType::class,
            [
                'label' => 'Показывать',
                'required' => false,
                
            ]
        )->add('title',
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
        )->add('date',
            TextType::class,
            [
                'label' => 'Дата создания',
                'empty_data' => false,
                'disabled' => true
            ]
        )->add('date_views',
            DateTimeType::class,
            [
                'label' => 'Дата публикации',
                'empty_data' => false,
            ]
        )->add('edited_on',
            TextType::class,
            [
                'label' => 'Дата изменения',
                'empty_data' => false,
                'disabled' => true
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
        )->add('tpl',
            ChoiceType::class, [
            'choices' => $this->getTplLayout(),
            'by_reference' => false,
            'placeholder' => 'Выбрать шаблон',
            'required' => true,
            'label' => 'Шаблон слайдера',
        ]
        )->add('id',
            TextType::class,
            [
                'label' => 'ID слайдера',
                'empty_data' => false,
                'disabled' => true,
                'required' => false,
            ]
        ); 

        $builder->add('slide', CollectionType::class, [
            'entry_type' => SlideType::class,
            'by_reference' => false,
            //'entry_options' => ['label' => false],
            'allow_add' => true,
            'allow_delete' => true,
            'allow_sequence' => false,
            'required' => false,
            'label' => 'Слайды',
            //'profession' => $options['data'],
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Slider::class,
        ));
    }

    private function getTplLayout(){
        $ar = [];
        foreach (glob($_SERVER['DOCUMENT_ROOT']."/src/Frontend/Modules/EnerSliders/Layout/Widgets/*.html.twig") as $filename) {
            $filename = explode('/',$filename);
            $filename = end($filename);
            $ar[$filename] = $filename;
        }
        return $ar;
    }

}
