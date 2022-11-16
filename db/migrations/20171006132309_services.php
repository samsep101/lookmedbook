<?php

use Phinx\Migration\AbstractMigration;

class Services extends AbstractMigration
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
        $this->dropTable('services_categories');
        $this->table('service_category')
            ->addColumn('service_type', 'string', ['limit' => 32])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('alias', 'string', ['limit' => 255])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('parent_id', 'integer', ['null' => true])
            ->addColumn('created_at', 'integer', ['signed' => false])
            ->addColumn('updated_at', 'integer', ['signed' => false])
            ->addColumn('priority', 'integer', ['default' => 0])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('docdoc_id', 'integer', ['null' => true])
            ->addColumn('linked_service_id', 'integer', ['null' => true])
            ->addIndex(['docdoc_id', 'service_type'], ['unique' => true])
            ->create();

        $this->dropTable('services_to_clinic');
        $this->table('service_to_clinic')
            ->addColumn('clinic_id', 'integer')
            ->addColumn('service_id', 'integer')
            ->addColumn('price', 'integer', ['signed' => false])
            ->addForeignKey('clinic_id', $this->table('clinic'))
            ->addForeignKey('service_id', $this->table('service_category'))
            ->create();
    }
}
