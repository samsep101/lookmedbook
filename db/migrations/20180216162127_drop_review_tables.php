<?php

use Phinx\Migration\AbstractMigration;

class DropReviewTables extends AbstractMigration
{
    public function up()
    {
        $this->dropTable('doctor_review');
        $this->dropTable('clinic_review');
    }

    public function down()
    {
        $this->table('doctor_review')
            ->addColumn('visit_id', 'integer')
            ->addColumn('account_id', 'integer')
            ->addColumn('doctor_id', 'integer')
            ->addColumn('text', 'text')
            ->addColumn('dt', 'timestamp')
            ->addColumn('is_confirmed', 'boolean')
            ->addForeignKey('visit_id', 'visit', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->addForeignKey('account_id', 'account', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->addForeignKey('doctor_id', 'doctor', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->addIndex('is_confirmed')
            ->create();
        $this->table('clinic_review')
            ->addColumn('visit_id', 'integer')
            ->addColumn('account_id', 'integer')
            ->addColumn('clinic_id', 'integer')
            ->addColumn('text', 'text')
            ->addColumn('dt', 'timestamp')
            ->addColumn('is_confirmed', 'boolean')
            ->addForeignKey('visit_id', 'visit', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->addForeignKey('account_id', 'account', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->addForeignKey('clinic_id', 'clinic', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->create();
    }
}
