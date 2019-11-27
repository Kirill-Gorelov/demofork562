<?php

namespace Backend\Modules\EnerIblocks\Domain\CategoryElements;

use Doctrine\ORM\EntityRepository;
use Backend\Core\Engine\Model as BackendModel;


class CategoryElementRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }

    public function add(CategoryElements $CategoryElements)
    {
        $this->getEntityManager()->persist($CategoryElements);
        $this->getEntityManager()->flush();
    }

    public function delete(CategoryElements $CategoryElements): void
    {
        $this->getEntityManager()->remove($CategoryElements);
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
        // return (array) BackendModel::getContainer()->get('database')->getRecords(
        //     'SELECT * FROM category_element WHERE category = ?',
        //     [(int) $id]
        // );
        return BackendModel::getContainer()->get('database')->insert('category_element', $item);
    }
}
