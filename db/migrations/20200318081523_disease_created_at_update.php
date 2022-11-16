<?php

use Phinx\Migration\AbstractMigration;

class DiseaseCreatedAtUpdate extends AbstractMigration
{
    public function up()
    {
        $this->query("update disease set created_at = date_update WHERE date_update > '2000-01-01'");
    }
}
