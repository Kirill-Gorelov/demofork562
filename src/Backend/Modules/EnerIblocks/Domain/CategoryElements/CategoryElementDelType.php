<?php

namespace Backend\Modules\EnerIblocks\Domain\CategoryElements;

use Backend\Form\Type\DeleteType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/*
 * Перенастройка формы удаления товара
 */
final class CategoryElementDelType extends DeleteType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $options['action'] = 'category_element_del_type';
        parent::buildForm($builder, $options);

        $builder->add('id', HiddenType::class);
    }
}
