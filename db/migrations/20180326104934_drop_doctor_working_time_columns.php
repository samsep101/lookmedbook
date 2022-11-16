<?php

use Phinx\Migration\AbstractMigration;

class DropDoctorWorkingTimeColumns extends AbstractMigration
{
    public function up()
    {
        $this->table('doctor')
            ->removeColumn('start_time_monday')
            ->removeColumn('end_time_monday')
            ->removeColumn('start_time_tuesday')
            ->removeColumn('end_time_tuesday')
            ->removeColumn('start_time_wednesday')
            ->removeColumn('end_time_wednesday')
            ->removeColumn('start_time_thursday')
            ->removeColumn('end_time_thursday')
            ->removeColumn('start_time_friday')
            ->removeColumn('end_time_friday')
            ->removeColumn('start_time_saturday')
            ->removeColumn('end_time_saturday')
            ->removeColumn('start_time_sunday')
            ->removeColumn('end_time_sunday')
            ->update();
    }

    public function down()
    {
        $this->table('doctor')
            ->addColumn('start_time_monday', 'string', ['null' => true, 'limit' => 10])
            ->addColumn('end_time_monday', 'string', ['null' => true, 'limit' => 10])
            ->addColumn('start_time_tuesday', 'string', ['null' => true, 'limit' => 10])
            ->addColumn('end_time_tuesday', 'string', ['null' => true, 'limit' => 10])
            ->addColumn('start_time_wednesday', 'string', ['null' => true, 'limit' => 10])
            ->addColumn('end_time_wednesday', 'string', ['null' => true, 'limit' => 10])
            ->addColumn('start_time_thursday', 'string', ['null' => true, 'limit' => 10])
            ->addColumn('end_time_thursday', 'string', ['null' => true, 'limit' => 10])
            ->addColumn('start_time_friday', 'string', ['null' => true, 'limit' => 10])
            ->addColumn('end_time_friday', 'string', ['null' => true, 'limit' => 10])
            ->addColumn('start_time_saturday', 'string', ['null' => true, 'limit' => 10])
            ->addColumn('end_time_saturday', 'string', ['null' => true, 'limit' => 10])
            ->addColumn('start_time_sunday', 'string', ['null' => true, 'limit' => 10])
            ->addColumn('end_time_sunday', 'string', ['null' => true, 'limit' => 10])
            ->update();
    }
}
