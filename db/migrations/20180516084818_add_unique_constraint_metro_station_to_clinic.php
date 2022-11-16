<?php

use Phinx\Migration\AbstractMigration;

class AddUniqueConstraintMetroStationToClinic extends AbstractMigration
{
    public function change()
    {
        // delete duplicates
        $this->query('
            DELETE ms2c
            FROM metro_station_to_clinic ms2c
              JOIN metro_station_to_clinic mstc
                USING (clinic_id, metro_station_id)
            WHERE ms2c.id > mstc.id
              AND ms2c.clinic_id = mstc.clinic_id
              AND ms2c.metro_station_id = mstc.metro_station_id
        ');

        $this->table('metro_station_to_clinic')
            ->addIndex(['clinic_id', 'metro_station_id'], ['unique' => true])
            ->update();

        $this->query("
            insert into metro_branch (name, metro_id) values
            ('Московское центральное кольцо', 1)
        ");
    }
}
