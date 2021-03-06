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

    public function getElementMeta($element){
        $parameter = [];
        if(empty($element['1'])){
            $sql = 'SELECT * FROM category_meta_value WHERE eid = ?';
            $parameter = [$element['id']];
        }else{
            $id = array_column($element, 'id');
            $sql = 'SELECT * FROM category_meta_value WHERE eid in ('.implode(", ", $id).')';
        }
        // var_dump($sql);
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

    public function getDefaultMetaValueForSelect($cti, $elem){
        return (array) BackendModel::getContainer()->get('database')->getRecords(
            'SELECT * FROM category_meta_select_value
            WHERE xml_id = ? and cti = ?  ORDER BY `id` DESC',
            [$elem, $cti]
        );
    }

    public function insertDefaultMetaValueForSelect($data){
        BackendModel::getContainer()->get('database')->insert(
            'category_meta_select_value',
            $data
        );
    }

    public function clearDefaultMetaValueForSelect($cti,$elem){
        BackendModel::getContainer()->get('database')->delete(
            'category_meta_select_value',
            'xml_id = ? and cti = ?',
            [$elem, $cti]
        );
    }

}
