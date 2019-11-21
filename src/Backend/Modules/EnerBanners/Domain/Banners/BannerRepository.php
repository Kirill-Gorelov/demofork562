<?php

namespace Backend\Modules\EnerBanners\Domain\Banners;

use Doctrine\ORM\EntityRepository;
use Backend\Modules\EnerBanners\Domain\Banners\Banner;

class BannerRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }

    public function add(Banner $Banner)
    {
        $this->getEntityManager()->persist($Banner);
        $this->getEntityManager()->flush();
    }

    public function delete(Banner $Banner): void
    {
        $this->getEntityManager()->remove($Banner);
        $this->getEntityManager()->flush();
    }

    public function getBanner(int $id){
        $rez = $this->createQueryBuilder('b')
        ->where('b.id = :id and b.active = 1 and b.date_views < :now')
        // ->setMaxResults(1)
        ->setParameter('id', $id)
        ->setParameter('now', new \DateTime('now'))
        ->getQuery()
        ->getResult();

        if (!empty($rez)) {
            $rez = $rez[0]; //TODO: или ????
            // $rez = end($rez);
            $count = $rez->views_count + 1;
            $this->updateViewsCount($id, $count);
            return $rez;
        }

        return '';
    }

    public function updateViewsCount($id, $count){
        $rez = $this->createQueryBuilder('b')
        ->update('Backend\Modules\EnerBanners\Domain\Banners\Banner', 'b')
        ->set('b.views_count', $count)
        ->where('b.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->execute();
    }

}
