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
