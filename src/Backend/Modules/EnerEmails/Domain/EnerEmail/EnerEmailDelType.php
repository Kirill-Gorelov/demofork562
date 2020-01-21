<?php

namespace Backend\Modules\EnerEmails\Domain\EnerEmail;

use Backend\Form\Type\DeleteType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;


final class EnerEmailDelType extends DeleteType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $options['action'] = 'Delete';
        parent::buildForm($builder, $options);

        $builder->add('id', HiddenType::class);
    }
}
