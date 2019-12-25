<?php

namespace Backend\Modules\EnerShop\Domain\Settings;

use Backend\Core\Engine\Model as BackendModel;

class Setting 
{
    public function update()
    {
        return (bool) BackendModel::getContainer()->get('database')->getVar(
            'SELECT id
             FROM imscevents
             WHERE id = ?',
            [(int) $id]
        );
    }

    public static function getAll()
    {
        return (array) BackendModel::getContainer()->get('database')->getRecords(
            'SELECT *
             FROM shop_settings
             WHERE 1',
            []
        );
    }

    public function get($key)
    {
        return BackendModel::getContainer()->get('database')->getVar(
            'SELECT `value`
             FROM shop_settings
             WHERE `key` = ?',
            [$key]
        );
    }
}
