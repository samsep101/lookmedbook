<?php

use Phinx\Migration\AbstractMigration;

class UpdateCityOrder extends AbstractMigration
{
    public function up()
    {
        $citiesOrdered = [
            'moskva',
            'sankt-peterburg',
            'novosibirsk',
            'ekaterinburg',
            'nizhniy-novgorod',
            'kazan',
            'samara',
            'omsk',
            'chelyabinsk',
            'perm',
            'krasnodar',
            'voronezh',
            'rostov-na-donu',
            'ufa',
            'izhevsk',
            'krasnoyarsk',
        ];
        foreach (array_reverse($citiesOrdered) as $sort => $alias) {
            $sort++;
            $this->query("update city set sort = $sort where alias = '$alias'");
        }
    }
}
