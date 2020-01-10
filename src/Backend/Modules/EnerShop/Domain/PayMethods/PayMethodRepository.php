<?php

namespace Backend\Modules\EnerShop\Domain\PayMethods;

use Doctrine\ORM\EntityRepository;
use Backend\Core\Engine\Model as BackendModel;

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
            'SELECT * FROM shop_method_pay WHERE active = 1'
        );
    }

    public function getElement(int $id)
    {
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT * FROM shop_method_pay WHERE id = ?',
            [$id]
        );
    }
    
}
