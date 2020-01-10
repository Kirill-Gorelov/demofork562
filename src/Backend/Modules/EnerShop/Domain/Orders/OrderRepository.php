<?php

namespace Backend\Modules\EnerShop\Domain\Orders;

use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }

    public function add(Order $Order)
    {
        $this->getEntityManager()->persist($Order);
        $this->getEntityManager()->flush();
    }

    public function delete(Order $Order): void
    {
        $this->getEntityManager()->remove($Order);
        $this->getEntityManager()->flush();
    }

    // shop_order_user_property
    // shop_order_product
    // shop_order_history_status
    
}
