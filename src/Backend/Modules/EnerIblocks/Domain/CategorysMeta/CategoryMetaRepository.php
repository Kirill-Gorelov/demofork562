<?php

namespace Backend\Modules\EnerIblocks\Domain\CategorysMeta;

use Doctrine\ORM\EntityRepository;
use Backend\Core\Engine\Model as BackendModel;


class CategoryMetaRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }

    public function add(CategoryMeta $CategoryMeta)
    {
        $this->getEntityManager()->persist($CategoryMeta);
        $this->getEntityManager()->flush();
    }

    public function delete(CategoryMeta $CategoryMeta): void
    {
        $this->getEntityManager()->remove($CategoryMeta);
        $this->getEntityManager()->flush();
    }

    public function getMetaByType(int $id){
        return (array) BackendModel::getContainer()->get('database')->getRecords(
            'SELECT * FROM category_meta
             WHERE cmeta_id = ?',
            [(int) $id]
        );
    }

    public function getElementMeta($id){
        $parameter = [];
        if(is_array($id)){
            // $sql = 'SELECT * FROM category_meta_value WHERE eid in (?)'; //FIXME: так почему-то не работает или возвращает только первый элемент из списка
            $sql = 'SELECT * FROM category_meta_value WHERE eid in ('.implode(", ", $id).')';
            $parameter = '';
        }else{
            $sql = 'SELECT * FROM category_meta_value WHERE eid = ?';
            $parameter = [$id];
        }
        return (array) BackendModel::getContainer()->get('database')->getRecords(
            $sql,
            $parameter
        );
    }

    public function insert_meta($insert_meta){
        BackendModel::getContainer()->get('database')->insert(
            'category_meta_value',
            $insert_meta
        );
    }

    public function delete_meta(int $id){
        BackendModel::getContainer()->get('database')->delete(
            'category_meta_value',
            'eid = ?',
            [(int)$id]
        );
    }

    public function update_meta($data):void
    {   
        //TODO: потому что как написать тут запрос на обновление нескольких записей в форке я не знаю.
        foreach ($data as $key => $value) {
            BackendModel::getContainer()->get('database')->update(
                'category_meta_value',
                $value,
                'id = ?',
                [$value['id']]
            );
        }
    }

}
