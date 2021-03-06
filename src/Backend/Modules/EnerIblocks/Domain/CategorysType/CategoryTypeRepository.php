<?php

namespace Backend\Modules\EnerIblocks\Domain\CategorysType;

use Doctrine\ORM\EntityRepository;
use Backend\Core\Engine\Model as BackendModel;


class CategoryTypeRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }

    public function add(CategoryType $Category)
    {
        $this->getEntityManager()->persist($Category);
        $this->getEntityManager()->flush();
    }

    public function delete(CategoryType $Category): void
    {
        $this->getEntityManager()->remove($Category);
        $this->getEntityManager()->flush();
    }

    public function getAllCategoryActive(string $category){
        return  $this->createQueryBuilder('c')
        ->where('c.maincategory = :category')
        ->setParameter('category', $category)
        ->getQuery()
        ->getResult();
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

    public function customsave(int $id, $data):void
    {
        BackendModel::getContainer()->get('database')->update(
            'category',
            $data,
            'id = ?',
            [$id]
        );
    }


}
