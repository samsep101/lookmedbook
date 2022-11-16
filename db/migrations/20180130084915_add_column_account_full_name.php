<?php

use Phinx\Migration\AbstractMigration;

class AddColumnAccountFullName extends AbstractMigration
{
    public function up()
    {
        $this->table('account')
            ->changeColumn('dt', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('full_name', 'string', ['null' => true, 'default' => null])
            ->update();
        $this->execute("
            UPDATE account
            SET full_name = concat_ws(
                ' ',
                nullif(last_name, ''),
                nullif(first_name, ''),
                nullif(middle_name, '')
            )            
        ");
    }

    public function down()
    {
        $this->table('account')
            ->removeColumn('full_name')
            ->changeColumn('dt', 'datetime', ['null' => true, 'default' => '0000-00-00 00:00:00'])
            ->update();
    }
}
