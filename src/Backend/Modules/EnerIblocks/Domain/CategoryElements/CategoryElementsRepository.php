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
}
