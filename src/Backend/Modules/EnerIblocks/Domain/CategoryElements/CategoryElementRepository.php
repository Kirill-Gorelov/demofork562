<?php

namespace Backend\Modules\EnerIblocks\Domain\CategoryElements;

use Doctrine\ORM\EntityRepository;
use Backend\Core\Language\Locale;
use DateTime;
use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\Model as BackendModel;


class CategoryElementRepository extends EntityRepository
{
    //TODO: сделать выборку в зависимости от языка
    public function getAllElementsById($id){
        return (array) BackendModel::getContainer()->get('database')->getRecords(
            'SELECT * FROM category_element WHERE category = ?',
            [(int) $id]
        );
    }

    public function getElement($id){
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT * FROM category_element WHERE id = ?',
            [(int) $id]
        );
    }

    public function add($item){
        //TODO:добавить время, кто создал, когда
        //TODO:объеденить добавление меты и элемента

        $item['language'] = Locale::workingLocale();
        $data['creator_user_id'] = Authentication::getUser()->getUserId();;
        $data['editor_user_id'] = Authentication::getUser()->getUserId();;
        $data['edited_on'] = new DateTime();
        $data['date'] = new DateTime();
        
        return BackendModel::getContainer()->get('database')->insert('category_element', $item);
    }


    public function update(int $id, $data):void
    {
        $data['editor_user_id'] = Authentication::getUser()->getUserId();;
        $data['edited_on'] = new DateTime();
        BackendModel::getContainer()->get('database')->update(
            'category_element',
            $data,
            'id = ?',
            [$id]
        );
    }

}
