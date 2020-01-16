<?php

namespace Backend\Modules\EnerShop\Domain\StatusOrders;

use Doctrine\ORM\EntityRepository;

class StatusOrderRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }

    public function add(StatusOrder $StatusOrder)
    {
        $this->getEntityManager()->persist($StatusOrder);
        $this->getEntityManager()->flush();
    }

    public function delete(StatusOrder $StatusOrder): void
    {
        $this->getEntityManager()->remove($StatusOrder);
        $this->getEntityManager()->flush();
    }

    public function getElement(int $id)
    {
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT * FROM shop_order_status WHERE id = ?',
            [$id]
        );
    }
    
}
