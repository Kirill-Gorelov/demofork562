<?php

namespace Backend\Modules\EnerSliders\Domain\Sliders;

use Doctrine\ORM\EntityRepository;
use Backend\Modules\EnerSliders\Domain\Sliders\Slider;
// use Backend\Modules\EnerSliders\Domain\Sliders\Slide;

class SliderRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }

    public function add(Slider $slider)
    {
        $this->getEntityManager()->persist($slider);
        $this->getEntityManager()->flush();
    }

    public function delete(Slider $slider): void
    {
        $this->getEntityManager()->remove($slider);
        $this->getEntityManager()->flush();
    }

    
    public function getSlider(int $id){
        $rez = $this->createQueryBuilder('b')
        ->select('b, s')
        ->leftJoin('b.slide','s')
        ->where('b.id = :id and b.active = 1 and b.date_views < :now')
        ->setParameter('id', $id)
        ->setParameter('now', new \DateTime('now'))
        ->getQuery()
        ->getResult();
        // var_dump($rez->getSql());

        if (!empty($rez)) {
            $rez = $rez[0]; //TODO: или ????
            return $rez;
        }

        return '';
    }
    
}
