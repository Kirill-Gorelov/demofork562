<?php

namespace Backend\Modules\EnerIblocks\Domain\CategoryElements;

use Doctrine\ORM\EntityRepository;
use Backend\Core\Language\Locale;
use DateTime;
use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\Model as BackendModel;


class CategoryElementRepository extends EntityRepository
{
    private $listColumn = ['id', 'title', 'description', 'active', 'category', 'code', 'date'];

    public function delete(CategoryElement $CategoryElement): void
    {
        $this->getEntityManager()->remove($CategoryElement);
        $this->getEntityManager()->flush();
    }


    public function getAllElementsById($id){
        return (array) BackendModel::getContainer()->get('database')->getRecords(
            'SELECT * FROM category_element WHERE category = ?',
            [(int) $id]
        );
    }

    public function getElement($id){
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT * FROM category_element ce  LEFT JOIN category_element_shop  ces ON ce.id = ces.eid WHERE ce.id = ?',
            [(int) $id]
        );
    }

    public function add($item){
        $item['language'] = Locale::workingLocale();
        $item['creator_user_id'] = Authentication::getUser()->getUserId();
        $item['editor_user_id'] = Authentication::getUser()->getUserId();
        $item['edited_on'] = new DateTime();
        $item['date'] = new DateTime();
        
        return BackendModel::getContainer()->get('database')->insert('category_element', $item);
    }

    public function insert_price($item){
        return BackendModel::getContainer()->get('database')->insert('category_element_shop', $item);
    }

    public function delete_price($id){
        BackendModel::getContainer()->get('database')->delete(
            'category_element_shop',
            'eid = ?',
            [(int)$id]
        );
    }

    public function update_price($data):void
    {
        BackendModel::getContainer()->get('database')->update(
            'category_element_shop',
            $data,
            'eid = ?',
            [$data['eid']]
        );
    }


    public function update(int $id, $data):void
    {
        $data['editor_user_id'] = Authentication::getUser()->getUserId();
        $data['edited_on'] = new DateTime();
        BackendModel::getContainer()->get('database')->update(
            'category_element',
            $data,
            'id = ?',
            [$id]
        );
    }

    public function getList($order = [], $filter = []){

        if (empty($order)) {//вдруг кто-то отправит пустой массив
            $order = ['id'=>'ASC'];
        }

        $order_column = trim(key($order));
        $order_type = trim($order[key($order)]);

        $query = 'SELECT * FROM category_element as ce WHERE 1 ';
        $this->parameters = [];

        if (!empty($filter)) {
            foreach ($filter as $key => $value) {
                if (!in_array($key, $this->listColumn)) { // если кто-то решит отфильтровать поля, которых нету в таблице
                    continue;
                }

                $query .= $this->presql($key, $value);
            }
        }

        $query .= ' ORDER BY '. $order_column .' '. $order_type;

        $extras = (array) BackendModel::getContainer()->get('database')->getRecords($query, $this->parameters);

        // var_export($extras);
        return !empty($extras) ? $extras : false;
    }

    private function presql($key, $value){

        if (is_array($value)) {
            // return ' AND ce.'.$key.' in (?)';//FIXME: так почему-то не работает или возвращает только первый элемент из списка
            return ' AND ce.'.$key.' in ('.implode(", ", $value).')';
        }
        
        if(substr($value, 0,1) == '%' || substr($value, -1) == '%'){
            $this->parameters[] = $value;
            return ' AND ce.'.$key.' like ?';
        }
        
        if(substr($value, 0,1) == '>' || substr($value, 0, 1) == '<'){
            $this->parameters[] = substr($value, -1);
            return ' AND ce.'.$key.' '.substr($value, 0,1).' ?';
        }
        
        $this->parameters[] = $value;
        return ' AND ce.'.$key.' = ?';
    }

}
