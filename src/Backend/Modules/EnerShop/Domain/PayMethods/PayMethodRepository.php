<?php

namespace Backend\Modules\EnerShop\Domain\PayMethods;

use Doctrine\ORM\EntityRepository;
// use Backend\Modules\EnerShop\Domain\Sliders\Slider;

class PayMethodRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }

    public function add(PayMethod $PayMethod)
    {
        $this->getEntityManager()->persist($PayMethod);
        $this->getEntityManager()->flush();
    }

    public function delete(PayMethod $PayMethod): void
    {
        $this->getEntityManager()->remove($PayMethod);
        $this->getEntityManager()->flush();
    }
    
}
