<?php

use Phinx\Migration\AbstractMigration;

class AddColumnDoctorAboutInternal extends AbstractMigration
{
    public function change()
    {
        $this->table('doctor')
            ->addColumn('about_internal', 'text', ['null' => true, 'default' => null])
            ->update();
        $this->table('moderate_doctor_information')
            ->addColumn('about_internal', 'text', ['null' => true, 'default' => null])
            ->update();
    }
}
