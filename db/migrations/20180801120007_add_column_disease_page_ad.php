<?php

use Phinx\Migration\AbstractMigration;

class AddColumnDiseasePageAd extends AbstractMigration
{
    public function change()
    {
        $this->table('disease')
            ->addColumn('page_ad', 'text', ['null' => true])
            ->update();
    }
}
