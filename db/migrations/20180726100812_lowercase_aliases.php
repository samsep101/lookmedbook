<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Migration\IrreversibleMigrationException;

class LowercaseAliases extends AbstractMigration
{
    public function up()
    {
        $tables = ['clinic', 'doctor'];
        foreach ($tables as $table) {
            $this->query("
                update `$table` set 
                  old_alias = alias,
                  alias = lower(alias)
                where alias regexp binary '[A-Z]'
            ");
        }
        $tables = ['action', 'street', 'disease', 'product', 'product_category'];
        foreach ($tables as $table) {
            $this->query("
                update `$table` set 
                  alias = lower(alias)
                where alias regexp binary '[A-Z]'
            ");
        }
    }

    public function down()
    {
        throw new IrreversibleMigrationException();
    }
}
