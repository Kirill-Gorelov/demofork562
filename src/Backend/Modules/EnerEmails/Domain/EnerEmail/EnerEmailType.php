<?php

namespace Backend\Modules\EnerEmails\Domain\EnerEmail;

use Backend\Core\Engine\Model;
//use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Backend\Form\Type\MetaType;
use Common\Form\CollectionType;
use Backend\Form\Type\EditorType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EnerEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('subject',
            TextType::class,
            [
                'label' => 'Тема',
                'empty_data' => false
            ]
        )->add('efrom',
            TextType::class,
            [
                'label' => 'От кого',
                'empty_data' => false
            ]
        )->add('email',
            TextType::class,
            [
                'label' => 'Кому',
                'empty_data' => false
            ]
        )->add('ecopy',
            TextType::class,
            [
                'label' => 'Копия',
                'empty_data' => false,
                'required' => false,
            ]
        )->add('template',
            ChoiceType::class, 
			[
				'choices' => $this->getTplLayout(),
				'by_reference' => false,
				'placeholder' => 'Выбрать шаблон',
				'required' => true,
				'label' => 'Шаблон письма',
			]
		);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => EnerEmail::class,
        ));
    }
	
	private function getTplLayout(){
        $ar = [];
        foreach (glob($_SERVER['DOCUMENT_ROOT']."/src/Backend/Modules/EnerEmails/Layout/Templates/Email/*.html.twig") as $filename) {
            $filename = explode('/',$filename);
            $filename = end($filename);
            $ar[$filename] = $filename;
        }
        return $ar;
    }
}
