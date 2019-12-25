<?php

namespace Backend\Modules\EnerShop\Domain\DeliveryMethods;

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
// use Backend\Modules\EnerShop\Domain\Slides\SlideType;

use Common\Form\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Backend\Modules\TechCourses\Forms\SpeakerType;

class DeliveryMethodType extends AbstractType
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
        )->add('image',
            TextType::class,
            [
                'label' => 'Логотип',
                'empty_data' => false,
                'required' => false,
                'attr' => ['class'=>'mediaselect'],
            ]
        )->add('price',
            TextType::class,
            [
                'label' => 'Стомость доставки',
                'empty_data' => false,
                'required' => false,
            ]
        )->add('sort',
            TextType::class,
            [
                'label' => 'Сортировка',
                'required' => true,
            ]
        )->add('code',
            TextType::class,
            [
                'label' => 'Символьный код',
                'required' => true,
            ]
        )->add('processor',
            TextType::class,
            [
                'label' => 'Обработчик',
                'required' => false,
            ]
        ); 

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => DeliveryMethod::class,
        ));
    }

    // private function getTplLayout(){
    //     $ar = [];
    //     foreach (glob($_SERVER['DOCUMENT_ROOT']."/src/Frontend/Modules/EnerSliders/Layout/Widgets/*.html.twig") as $filename) {
    //         $filename = explode('/',$filename);
    //         $filename = end($filename);
    //         $ar[$filename] = $filename;
    //     }
    //     return $ar;
    // }

}
