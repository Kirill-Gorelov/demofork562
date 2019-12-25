<?php

namespace Backend\Modules\EnerShop\Domain\Banners;

use Doctrine\ORM\EntityRepository;

class SettingRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }
}
