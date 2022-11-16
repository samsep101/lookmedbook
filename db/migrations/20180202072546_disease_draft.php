<?php

use Phinx\Migration\AbstractMigration;

class DiseaseDraft extends AbstractMigration
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
        $this->table('disease_draft')
            ->addColumn('name', 'string', ['null' => false, 'limit' => 255])
            ->addColumn('genitive_name', 'string', ['null' => false, 'limit' => 255])
            ->addColumn('prepositional_name', 'string', ['null' => false, 'limit' => 255])
            ->addColumn('alternative_names', 'string', ['null' => true, 'limit' => 1024])
            ->addColumn('tags', 'string', ['null' => true, 'limit' => 1024])
            ->addColumn('content', 'text', ['null' => true])
            ->addColumn('extended_content', 'text', ['null' => true])
            ->addColumn('sources', 'text', ['null' => true])
            ->addColumn('status', 'integer', ['null' => false])
            ->addColumn('disease_id', 'integer', ['null' => true])
            ->addColumn('created_at', 'integer', ['null' => false])
            ->addColumn('updated_at', 'integer', ['null' => false])
            ->create();

        $this->table('specialty_to_disease_draft')
            ->addColumn('specialty_id', 'integer', ['null' => false])
            ->addColumn('disease_draft_id', 'integer', ['null' => false])
            ->addColumn('is_adult', 'boolean', ['null' => true])
            ->addColumn('is_male', 'boolean', ['null' => true])
            ->addColumn('is_female', 'boolean', ['null' => true])
            ->addColumn('is_children', 'boolean', ['null' => true])
            ->addColumn('is_newborn', 'boolean', ['null' => true])
            ->addColumn('is_pregnant', 'boolean', ['null' => true])
            ->addColumn('main_flag', 'boolean', ['null' => true])
            ->addColumn('created_at', 'integer', ['null' => false])
            ->addColumn('updated_at', 'integer', ['null' => false])
            ->addForeignKey('specialty_id', 'specialty', 'id')
            ->addForeignKey('disease_draft_id', 'disease_draft', 'id')
            ->create();

        $this->table('disease_draft_block')
            ->addColumn('disease_draft_id', 'integer', ['null' => false])
            ->addColumn('disease_block_type_id', 'integer', ['null' => false])
            ->addColumn('content', 'text', ['null' => true])
            ->addColumn('is_active', 'boolean', ['null' => true])
            ->addColumn('is_adult', 'boolean', ['null' => true])
            ->addColumn('is_male', 'boolean', ['null' => true])
            ->addColumn('is_female', 'boolean', ['null' => true])
            ->addColumn('is_children', 'boolean', ['null' => true])
            ->addColumn('is_newborn', 'boolean', ['null' => true])
            ->addColumn('is_pregnant', 'boolean', ['null' => true])
            ->addColumn('created_at', 'integer', ['null' => false])
            ->addColumn('updated_at', 'integer', ['null' => false])
            ->addForeignKey('disease_draft_id', 'disease_draft', 'id')
            ->create();
    }
}
