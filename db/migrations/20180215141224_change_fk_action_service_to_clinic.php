<?php

use Phinx\Migration\AbstractMigration;

class ChangeFkActionServiceToClinic extends AbstractMigration
{
    public function up()
    {
        $this->table('service_to_clinic')
            ->dropForeignKey('clinic_id')
            ->addForeignKey('clinic_id', 'clinic', 'id', ['delete' => 'CASCADE'])
            ->dropForeignKey('service_id')
            ->addForeignKey('service_id', 'service_category', 'id', ['delete' => 'CASCADE'])
            ->update();
    }

    public function down()
    {
        $this->table('service_to_clinic')
            ->dropForeignKey('clinic_id')
            ->addForeignKey('clinic_id', 'clinic', 'id')
            ->dropForeignKey('service_id')
            ->addForeignKey('service_id', 'service_category', 'id')
            ->update();
    }
}
