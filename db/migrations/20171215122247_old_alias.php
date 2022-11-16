<?php

require_once __DIR__ . '/../../application/library/helpers/string_transliteration.helper.php';
use Phinx\Migration\AbstractMigration;

class OldAlias extends AbstractMigration
{
    public function up()
    {
        $this->table('doctor')
            ->addColumn('old_alias', 'string', ['null' => true])
            ->update();

        $this->table('clinic')
            ->addColumn('old_alias', 'string', ['null' => true])
            ->update();

        // Миграция данных
        /**
         * @var PDOStatement $stmt
         * @var \Phinx\Db\Adapter\MysqlAdapter $adapter
         */
        $adapter = $this->getAdapter();
        echo "Обновление врачей\n";
        $stmt = $this->query('SELECT id, last_name, first_name, second_name, alias FROM doctor');
        $aliasCache = [];
        $total = $stmt->rowCount();
        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nameParts = array_filter(array_map(
                function ($e) {
                    return trim(StringTransliterationHelper::translit($e));
                },
                [$row['last_name'], $row['first_name'], $row['second_name']]
            ));

            $fullFio = implode('-', $nameParts);
            if ($row['alias'] !== $fullFio) {
                $newAlias = $fullFio;
                $newAliasFix = 0;
                do {
                    $flag = isset($aliasCache[$newAlias]);
                    if ($flag) {
                        $newAliasFix++;
                        $newAlias = "{$fullFio}$newAliasFix";
                    }
                } while ($flag);
                $innerStmt = $adapter->getConnection()->prepare('UPDATE doctor SET old_alias = alias, alias = :a WHERE id = :id');
                $innerStmt->bindValue(':a', $newAlias);
                $innerStmt->bindValue(':id', $row['id']);
                $innerStmt->execute();
                $aliasCache[$newAlias] = $newAlias;
            } else {
                $aliasCache[$row['alias']] = $row['alias'];
            }
            $i++;
            if ($i % 1000 === 0 || $i === $total) {
                echo "$i/$total\n";
            }
        }

        echo "Обновление клиник\n";
        $stmt = $this->query('SELECT id, `name`, alias FROM clinic');
        $aliasCache = [];
        $newAliases = [];
        $total = $stmt->rowCount();
        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name = StringTransliterationHelper::translit($row['name']);
            if ($row['alias'] !== $name) {
                $newAlias = $name;
                $newAliasFix = 0;
                do {
                    $flag = isset($aliasCache[$newAlias]);
                    if ($flag) {
                        $newAliasFix++;
                        $newAlias = "{$name}$newAliasFix";
                    }
                } while ($flag);
                $innerStmt = $adapter->getConnection()->prepare('UPDATE clinic SET old_alias = alias, alias = :a WHERE id = :id');
                $innerStmt->bindValue(':a', $newAlias);
                $innerStmt->bindValue(':id', $row['id']);
                $innerStmt->execute();
                $aliasCache[$newAlias] = $newAlias;
                $newAliases[$row['id']] = $newAlias;
            } else {
                $aliasCache[$row['alias']] = $row['alias'];
                $newAliases[$row['id']] = $row['alias'];
            }
            $i++;
            if ($i % 500 === 0 || $i === $total) {
                echo "$i/$total\n";
            }
        }

        echo "Обновление клиник с родительскими\n";
        $stmt = $this->query('SELECT id, alias, primary_clinic_id FROM clinic WHERE primary_clinic_id is not null AND primary_clinic_id <> 0 AND primary_clinic_id != id');
        $total = $stmt->rowCount();
        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $fullAlias = "{$newAliases[$row['primary_clinic_id']]}/{$row['alias']}";
            $innerStmt = $adapter->getConnection()->prepare('UPDATE clinic SET original_alias = alias, alias = :a WHERE id = :id');
            $innerStmt->bindValue(':a', $fullAlias);
            $innerStmt->bindValue(':id', $row['id']);
            $innerStmt->execute();
            $i++;
            if ($i % 500 === 0 || $i === $total) {
                echo "$i/$total\n";
            }
        }
    }

    public function down()
    {
        // Миграция данных
        /**
         * @var PDOStatement $stmt
         * @var \Phinx\Db\Adapter\MysqlAdapter $adapter
         */
        $adapter = $this->getAdapter();
        echo "Обновление врачей\n";
        $stmt = $this->query('SELECT id, alias, old_alias FROM doctor');
        $total = $stmt->rowCount();
        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['old_alias']) {
                $innerStmt = $adapter->getConnection()->prepare('UPDATE doctor SET alias = old_alias WHERE id = :id');
                $innerStmt->bindValue(':id', $row['id']);
                $innerStmt->execute();
            }
            $i++;
            if ($i % 1000 === 0 || $i === $total) {
                echo "$i/$total\n";
            }
        }

        echo "Обновление клиник\n";
        $stmt = $this->query('SELECT id, name, alias, old_alias FROM clinic');
        $total = $stmt->rowCount();
        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['old_alias']) {
                $innerStmt = $adapter->getConnection()->prepare('UPDATE clinic SET alias = old_alias WHERE id = :id');
                $innerStmt->bindValue(':id', $row['id']);
                $innerStmt->execute();
            }
            $i++;
            if ($i % 500 === 0 || $i === $total) {
                echo "$i/$total\n";
            }
        }

        // Миграция структуры
        $this->table('doctor')
            ->removeColumn('old_alias')
            ->update();

        $this->table('clinic')
            ->removeColumn('old_alias')
            ->update();
    }
}
