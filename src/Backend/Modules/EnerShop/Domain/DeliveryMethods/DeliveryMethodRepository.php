<?php

namespace Backend\Modules\EnerShop\Domain\DeliveryMethods;

use Doctrine\ORM\EntityRepository;
// use Backend\Modules\EnerShop\Domain\Sliders\Slider;

class DeliveryMethodRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }

    public function add(DeliveryMethod $DeliveryMethod)
    {
        $this->getEntityManager()->persist($DeliveryMethod);
        $this->getEntityManager()->flush();
    }

    public function delete(DeliveryMethod $DeliveryMethod): void
    {
        $this->getEntityManager()->remove($DeliveryMethod);
        $this->getEntityManager()->flush();
    }
    
}
