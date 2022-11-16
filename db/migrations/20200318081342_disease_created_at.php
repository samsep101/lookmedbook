<?php

use Phinx\Migration\AbstractMigration;

class DiseaseCreatedAt extends AbstractMigration
{
    public function change()
    {

        $this->table('disease')
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->update();
    }
}
