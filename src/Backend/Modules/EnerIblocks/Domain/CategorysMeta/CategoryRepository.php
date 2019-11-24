<?php

namespace Backend\Modules\EnerIblocks\Domain\CategorysMeta;

use Doctrine\ORM\EntityRepository;
use Backend\Core\Engine\Model as BackendModel;


class CategoryMetaRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }

    public function add(CategoryMeta $CategoryMeta)
    {
        $this->getEntityManager()->persist($CategoryMeta);
        $this->getEntityManager()->flush();
    }

    public function delete(CategoryMeta $CategoryMeta): void
    {
        $this->getEntityManager()->remove($CategoryMeta);
        $this->getEntityManager()->flush();
    }

}
