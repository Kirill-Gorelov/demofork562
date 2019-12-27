<?php

namespace Backend\Modules\EnerShop\Domain\ShopStatistics;

use Backend\Core\Engine\Model as BackendModel;

class ShopStatistic 
{
    public static function getAll()
    {
        return (array) BackendModel::getContainer()->get('database')->getRecords(
            'SELECT *
             FROM shop_settings
             WHERE 1',
            []
        );
    }

}
