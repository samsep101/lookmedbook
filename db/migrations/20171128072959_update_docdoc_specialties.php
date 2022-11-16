<?php

use Phinx\Migration\AbstractMigration;

class UpdateDocdocSpecialties extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('specialty')
             ->addColumn('docdoc_id', 'integer', ['null' => true])
             ->update();

        $this->table('service_category')
             ->addColumn('specialty_id', 'integer', ['null' => true])
             ->update();

        $this->table('docdoc_service_category')
             ->addColumn('docdoc_id', 'integer', ['null' => false])
             ->addColumn('full_name', 'string', ['limit' => 1024])
             ->addColumn('sector_id', 'integer', ['null' => true])
             ->create();
    }
}
