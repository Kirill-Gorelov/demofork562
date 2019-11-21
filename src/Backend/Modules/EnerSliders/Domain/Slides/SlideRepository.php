<?php

namespace Backend\Modules\EnerSliders\Domain\Slides;

use Doctrine\ORM\EntityRepository;

class SlideRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }

    public function add(Slide $oneslide)
    {
        $this->getEntityManager()->persist($oneslide);
        $this->getEntityManager()->flush();
    }

    public function delete(Slide $oneslide): void
    {
        $this->getEntityManager()->remove($oneslide);
        $this->getEntityManager()->flush();
    }

    public function getSlideById(int $id){
        // return $this->createQueryBuilder('o')->getQuery()->getResult();
        return  $this->createQueryBuilder('c')
        ->where('c.pagesliders = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getResult();
    }

}
