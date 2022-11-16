<?php

use Phinx\Migration\AbstractMigration;

class ClinicDoctorAddIsActiveUpdatedAt extends AbstractMigration
{

    public function up()
    {
        $this->table('doctor')
             ->addColumn('is_active_updated_at', 'integer', ['null'=>true])
             ->update();

        $this->table('clinic')
             ->addColumn('is_active_updated_at', 'integer', ['null'=>true])
             ->update();

        // Миграция данных
        /**
         * @var PDOStatement $stmt
         * @var \Phinx\Db\Adapter\MysqlAdapter $adapter
         */
        $adapter = $this->getAdapter();
        $forceDate = 1504224000;
        echo "Обновление врачей\n";
        $innerStmt = $adapter
            ->getConnection()
            ->prepare('UPDATE doctor SET is_active_updated_at = :t');
        $innerStmt->bindValue(':t', $forceDate);
        $innerStmt->execute();

        $innerStmt = $adapter
            ->getConnection()
            ->prepare('UPDATE doctor SET is_active_updated_at = UNIX_TIMESTAMP() WHERE docdoc_id is not null AND docdoc_id != 0');
        $innerStmt->execute();

        echo "Обновление клиник\n";
        $innerStmt = $adapter
            ->getConnection()
            ->prepare('UPDATE clinic SET is_active_updated_at = :t');
        $innerStmt->bindValue(':t', $forceDate);
        $innerStmt->execute();

        $innerStmt = $adapter
            ->getConnection()
            ->prepare('UPDATE clinic SET is_active_updated_at = UNIX_TIMESTAMP() WHERE docdoc_id is not null AND docdoc_id != 0');
        $innerStmt->execute();
    }

    public function down()
    {
        $this->table('doctor')
             ->removeColumn('is_active_updated_at')
             ->update();

        $this->table('clinic')
             ->removeColumn('is_active_updated_at')
             ->update();
    }
}
