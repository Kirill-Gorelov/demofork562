<?php

namespace Backend\Modules\EnerIblocks\Domain\Categorys;

use Doctrine\ORM\EntityRepository;
use Backend\Core\Engine\Model as BackendModel;


class CategoryRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }

    public function add(Category $Category)
    {
        $this->getEntityManager()->persist($Category);
        $this->getEntityManager()->flush();
    }

    public function delete(Category $Category): void
    {
        $this->getEntityManager()->remove($Category);
        $this->getEntityManager()->flush();
    }

    public function getCategory(int $id){
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT * FROM category
             WHERE id = ?',
            [(int) $id]
        );
    }

    //TODO: сделать выборку в зависимости от языка
    public function getAllCategory(){
        return BackendModel::getContainer()->get('database')->getRecords(
            'SELECT * FROM category'
        );
    }

    public function getCategorysById(int $id){
        return (array) BackendModel::getContainer()->get('database')->getRecords(
            'SELECT * FROM category
             WHERE parent = ?',
            [(int) $id]
        );
    }

    public function customsave(int $id, $data):void
    {
        BackendModel::getContainer()->get('database')->update(
            'category',
            $data,
            'id = ?',
            [$id]
        );
    }

    public function getCTId(int $id){
        $record = BackendModel::getContainer()->get('database')->getRecord(
            'SELECT * FROM category
            WHERE id = ?',
            [(int) $id]
        );
        //8

        if (intval($record['parent']) == 0) {
            return $record['id'];
        }

        return $this->getCTId($record['parent']); //Да, рекурсия, а как еще подняться в родительской категории, если я буду находится на 10 уровней ниже?

    }


}
