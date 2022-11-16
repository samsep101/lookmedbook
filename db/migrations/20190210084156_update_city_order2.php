<?php

use Phinx\Migration\AbstractMigration;

class UpdateCityOrder2 extends AbstractMigration
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
            'chelyabinsk',
            'perm',
            'krasnodar',
            'voronezh',
            'rostov-na-donu',
            'ufa',
            'izhevsk',
            'krasnoyarsk',
            'volgograd',
            'sochi',
            'tyumen',
            'yaroslavl'
        ];
        foreach (array_reverse($citiesOrdered) as $sort => $alias) {
            $sort++;
            $this->query("update city set sort = $sort where alias = '$alias'");
        }
    }
}
