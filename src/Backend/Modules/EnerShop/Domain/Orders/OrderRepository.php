<?php

namespace Backend\Modules\EnerShop\Domain\Orders;

use Doctrine\ORM\EntityRepository;
use DateTime;
use Backend\Core\Engine\Model as BackendModel;

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

    // shop_order_user_contacts
    // shop_order_product
    // shop_order_history_status

    public function insertOrderProduct(int $order_id, $data)
    {
        // var_dump($data);
        foreach ($data as $key => $value) {
            $item = [];
            $item['id_order'] = $order_id;
            $item['id_product'] = $value['id'];
            $item['title'] = $value['title'];
            $item['price'] = $value['price'];
            $item['item_price'] = $value['item_price'];
            $item['quantity'] = $value['quantity'];
            $item['discount'] = $value['discount'];
            $item['discount_price'] = $value['discount_price'];
            // $item['property'] = $value['property'];
            BackendModel::getContainer()->get('database')->insert('shop_order_product', $item);
        }

        return;
    }

    public function createOrder($data)
    {
        $data['date'] = new DateTime();
        $data['date_edit'] = new DateTime();
        return BackendModel::getContainer()->get('database')->insert('shop_order', $data);
    }

    public function getNextIdOrderNumber()
    {
        $r =  BackendModel::getContainer()->get('database')->getRecord(
            'SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_NAME = "shop_order"'
        );

        return intval($r['AUTO_INCREMENT']);
    }
    
}
