<?php

namespace Backend\Modules\EnerIblocks\Domain\CategoryElements;

use Doctrine\ORM\EntityRepository;
use Backend\Core\Language\Locale;
use Backend\Core\Engine\Model as BackendModel;


class CategoryElementRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }

    public function add(CategoryElement $CategoryElement)
    {
        $this->getEntityManager()->persist($CategoryElement);
        $this->getEntityManager()->flush();
    }

    public function delete(CategoryElement $CategoryElement): void
    {
        $this->getEntityManager()->remove($CategoryElement);
        $this->getEntityManager()->flush();
    }


    //TODO: сделать выборку в зависимости от языка
    public function getAllElementsById($id){
        return (array) BackendModel::getContainer()->get('database')->getRecords(
            'SELECT * FROM category_element WHERE category = ?',
            [(int) $id]
        );
    }

    public function getElement($id){
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT * FROM category_element WHERE id = ?',
            [(int) $id]
        );
    }

    public function insert($item){
        //TODO:добавить время, кто создал, когда
        //TODO:объеденить добавление меты и элемента

        $item['language'] = Locale::workingLocale();
        return BackendModel::getContainer()->get('database')->insert('category_element', $item);
    }


    public function updateCustom(int $id, $data):void
    {
        BackendModel::getContainer()->get('database')->update(
            'category_element',
            $data,
            'id = ?',
            [$id]
        );
    }

    /********************************** */

    // TODO: все что связанно с метой вынести в репозиторий с мета
    public function getElementMeta($id){
        return (array) BackendModel::getContainer()->get('database')->getRecords(
            'SELECT * FROM category_meta_value WHERE eid = ?',
            [(int) $id]
        );
    }

    public function insert_meta($insert_meta){
        BackendModel::getContainer()->get('database')->insert(
            'category_meta_value',
            $insert_meta
        );
    }

    public function delete_meta(int $id){
        BackendModel::getContainer()->get('database')->delete(
            'category_meta_value',
            'id = ?',
            [(int)$id]
        );
    }

    public function update_meta($data):void
    {   
        //TODO: потому что как написать тут запрос на обновление нескольких записей в форке я не знаю.
        foreach ($data as $key => $value) {
            BackendModel::getContainer()->get('database')->update(
                'category_meta_value',
                $value,
                'id = ?',
                [$value['id']]
            );
        }
    }
}
