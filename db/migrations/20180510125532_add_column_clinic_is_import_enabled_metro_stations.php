<?php

use Phinx\Migration\AbstractMigration;

class AddColumnClinicIsImportEnabledMetroStations extends AbstractMigration
{
    public function change()
    {
        $this->table('clinic')
            ->addColumn('is_import_of_metro_stations_enabled', 'boolean', [
                'null' => false,
                'default' => true,
            ])
            ->update();
    }
}
