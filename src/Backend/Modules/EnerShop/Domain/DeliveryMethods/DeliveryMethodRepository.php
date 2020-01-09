<?php

namespace Backend\Modules\EnerShop\Domain\DeliveryMethods;

use Doctrine\ORM\EntityRepository;
use Backend\Core\Engine\Model as BackendModel;

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

    public function getElements()
    {
        // $rez = $this->createQueryBuilder('d')
        // ->select('d')
        // ->where('d.active = 1')
        // // ->orderBy('d.id', 'ASC')
        // ->getQuery()
        // ->getResult();
        // // var_dump($rez->getSql());

        // return $rez;

        // if (!empty($rez)) {
        //     $rez = $rez[0]; //TODO: или ????
        //     return $rez;
        // }

        // return '';

        return (array) BackendModel::getContainer()->get('database')->getRecords(
            'SELECT * FROM shop_method_delivery WHERE active = 1'
        );
    }
    
}
