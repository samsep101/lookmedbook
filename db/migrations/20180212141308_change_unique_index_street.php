<?php

use Phinx\Migration\AbstractMigration;

class ChangeUniqueIndexStreet extends AbstractMigration
{
    public function up()
    {
        $this->execute('
            update clinic join (
              select clinic.id
              from clinic
                left join street on clinic.street_id = street.id
              where clinic.street_id is not null
                    and street.id is null
            ) t using(id)
            set street_id = null
        ');
        $this->execute("
            update ignore clinic
            set license_validity_date = null
            where license_validity_date = '0000-00-00'
        ");
        $this->execute("
            update ignore clinic
            set license_issue_date = null
            where license_issue_date = '0000-00-00'
        ");
        $this->table('clinic')
            ->addForeignKey('street_id', 'street', 'id', ['delete' => 'SET_NULL'])
            ->update();

        $this->execute('update street set city_id = 2');
        $this->table('street')
            ->removeIndex(['prefix', 'name'])
            ->addIndex(['city_id', 'prefix', 'name'], ['unique' => true])
            ->removeColumn('docdoc_id')
            ->update();
        $this->table('street_docdoc')
            ->addColumn('docdoc_id', 'integer', ['null' => false])
            ->addColumn('street_id', 'integer', ['null' => false])
            ->addForeignKey('street_id', 'street', 'id', ['delete' => 'CASCADE'])
            ->addIndex('docdoc_id', ['unique' => true])
            ->create();
    }

    public function down()
    {
        $this->dropTable('street_docdoc');
        $this->table('street')
            ->addColumn('docdoc_id', 'integer')
            ->removeIndex(['city_id', 'prefix', 'name'])
            ->addIndex(['prefix', 'name'], ['unique' => true])
            ->update();
        $this->execute('update street set city_id = null');

        $this->table('clinic')
            ->dropForeignKey('street_id')
            ->update();
    }
}
