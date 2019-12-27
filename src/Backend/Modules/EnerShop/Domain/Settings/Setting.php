<?php

namespace Backend\Modules\EnerShop\Domain\Settings;

use Backend\Core\Engine\Model as BackendModel;

class Setting 
{
    public static function update($data)
    {
        foreach ($data as $key => $value) {
            BackendModel::getContainer()->get('database')->update(
                'shop_settings',
                $value,
                '`key` = ?',
                [$key]
            );
        }
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
