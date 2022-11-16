<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Migration\IrreversibleMigrationException;

class FixClinicStreetId extends AbstractMigration
{
    public function up()
    {
        $this->execute('
            UPDATE clinic JOIN (
              SELECT
                clinic.id id,
                s.id street_id
              FROM clinic
                LEFT JOIN street
                  ON clinic.street_id = street.id
                JOIN street_docdoc sd
                  ON clinic.street_id = sd.docdoc_id
                JOIN street s
                  ON sd.street_id = s.id
              WHERE (locate(street.name, clinic.address) = 0
                     OR locate(street.name, clinic.address) IS NULL)
                    AND locate(s.name, clinic.address) > 0
              ) c USING (id)
            SET clinic.street_id = c.street_id        
        ');
    }

    public function down()
    {
        throw new IrreversibleMigrationException();
    }
}
