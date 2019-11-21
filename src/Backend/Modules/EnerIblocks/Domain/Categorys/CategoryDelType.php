<?php

namespace Backend\Modules\EnerIblocks\Domain\Categorys;

use Backend\Form\Type\DeleteType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/*
 * Перенастройка формы удаления товара
 */
final class CategoryDelType extends DeleteType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $options['action'] = 'delete';
        parent::buildForm($builder, $options);

        $builder->add('id', HiddenType::class);
    }
}
