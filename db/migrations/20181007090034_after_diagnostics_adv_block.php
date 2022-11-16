<?php

use Phinx\Migration\AbstractMigration;

class AfterDiagnosticsAdvBlock extends AbstractMigration
{

    public function change()
    {
        $this->table('disease')
            ->addColumn('page_ad_after_diagnostics', 'text', ['null' => true])
            ->update();
    }
}
