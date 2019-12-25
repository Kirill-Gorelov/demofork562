<?php

namespace Backend\Modules\EnerShop\Domain\Settings;

use Backend\Core\Engine\Model as BackendModel;

class Setting 
{
    public static function update($data)
    {
        // var_export($data);
        // var_dump($key, $value);
        foreach ($data as $key => $value) {
            var_dump($key);
            BackendModel::getContainer()->get('database')->update(
                'shop_settings',
                $value,
                'key = ?',
                [$value['key']]
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
