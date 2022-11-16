<?php

use Phinx\Migration\AbstractMigration;

class ClinicImportDisableField extends AbstractMigration
{
    public function change()
    {
        $this->table('clinic')
            ->addColumn('is_import_enabled', 'boolean', [
                'null' => false,
                'default' => true,
            ])
            ->update();
    }
}
