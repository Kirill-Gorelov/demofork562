<?php

namespace Backend\Modules\Imscevents\Form;

use Backend\Form\Type\DeleteType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/*
 * Перенастройка формы удаления мероприятия
 */
final class ImsceventsDelType extends DeleteType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $options['action'] = 'Delevent';
        parent::buildForm($builder, $options);

        $builder->add('id', HiddenType::class);
    }
}
