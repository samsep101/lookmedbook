<?php

use Phinx\Migration\AbstractMigration;

class AddColumnMetroStationMainStationId extends AbstractMigration
{
    public function change()
    {
        $this->table('metro_station')
            ->addColumn('main_station_id', 'integer', ['null' => true])
            ->update();
        $this->table('metro_station')
            ->addForeignKey('main_station_id', 'metro_station', 'id', [
                'delete' => 'SET NULL',
            ])
            ->update();

        $this->query("
            update metro_station join (
              select ms1.id, ms2.id as main_station_id
              from (
                select id, left(alias, length(alias) - 1) base_alias
                from metro_station
                where alias regexp '[a-z][0-9]$'
              ) ms1
              left join metro_station ms2 on ms1.base_alias = ms2.alias
            ) ms using(id)
            set metro_station.main_station_id = ms.main_station_id
        ");
        $this->query("
            update metro_station join (
              select distinct(main_station_id) as id
              from metro_station
            ) ms using(id)
            set metro_station.main_station_id = ms.id
        ");
        $this->query("
            update metro_station set 
              alias = 'metro-parnas' 
            where alias = 'metro-parnas0'
        ");
    }
}
