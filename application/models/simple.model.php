<?php

class SimpleModel {
    /** @author Playmore 2017 (playmoredevelop@gmail.com) */

    /**
     * @var Db
     */
	protected $db = null;
    protected $cityID = 2;

    public function __construct() {

        $this->db = Register::get('db');
    }

    public function setCityID($cityID) {

        ($cityID > 0) AND $this->cityID = (int)$cityID;
        return $this;
    }

    /**
	 * Метод обновления данных в таблице
	 * @param string $table
	 * @param array $data
	 * @param string $where
	 * @param int $limit
	 * @return affected num rows
	 */
	protected function update($table, $data, $where, $limit = 1) {

		$set = [];
		foreach($data as $field => $value){
			if(is_array($value) OR is_object($value)){
				$set[] = sprintf('`%s` = %s', $field, $this->escape(json_encode($value)));
				continue;
			}
			if(is_string($value)){
				$set[] = sprintf('`%s` = %s', $field, $this->escape($value));
				continue;
			}
			if(is_null($value)){
				$set[] = sprintf('`%s` = NULL', $field); continue;
			}
			if(empty($value)){
				$set[] = "`{$field}` = ''"; continue;
			}
			$set[] = sprintf('`%s` = %s', $field, $value);
		}

		$q = str_replace(['{table}', '{set}', '{where}', '{limit}'], [
			$table,
			implode(', ', $set),
			$where,
			(int)$limit
		], 'UPDATE {table} SET {set} WHERE {where} LIMIT {limit}');

		$q = $this->db->post($q);

		return $this->db->getAffectedRows();
	}

	/**
	 * Метод добавления данных в таблицу
	 * @param string $table
	 * @param array $data
	 * @return int|| false
	 */
	protected function insert($table, $data) {

		$fields = array_keys($data);
		$values = array_values($data);

		foreach($values as $i => $value){
			if(is_array($value) OR is_object($value)){
				$values[$i] = $this->escape(json_encode($value)); continue;
			}
			if(is_string($value)){
				$values[$i] = $this->escape($value); continue;
			}
            if(is_int($value)){
                $values[$i] = (int)$value; continue;
            }
			if(is_null($value)){
				$values[$i] = 'NULL'; continue;
			}
			if(empty($value)){
				$values[$i] = "''"; continue;
			}
		}

		$q = str_replace(['{table}', '{fields}', '{values}'], [
			$table,
			'`'.implode('`, `', $fields).'`',
			implode(', ', $values)
		], 'INSERT INTO `{table}` ({fields}) VALUES ({values})');

		if($this->db->post($q)){
			return $this->db->lastInsertId();
		} else {
			//debug($q);
			return false;
		}

	}

	/**
	 * Метод посчета кол-ва строк в таблице с условием
	 * @param string $table
	 * @param string $where
	 * @return int
	 */
	protected function total($table, $where = '') {

		$q = str_replace(['{table}', '{where}'], [
			$table,
			(!empty($where)) ? ' WHERE '.$where : ''
		], 'SELECT COUNT(1) as total FROM {table}{where}');

		$q = $this->db->query($q)[0];

		return intval($q['total']);
	}

	/**
	 *
	 * @param type $table
	 * @param type $limit
	 * @param type $offset
	 * @return array
	 */
	protected function get($table, $limit = false, $offset = 0, $fields = '*') {

        is_array($fields) AND $fields = implode(', ', $fields);

		$q = str_replace(['{fields}', '{table}', '{limit}', '{offset}'], [
            $fields,
			$table,
			($limit) ? ' LIMIT '.(int)$limit : '',
			($limit) ? ' OFFSET '.(int)$offset : '',
		], 'SELECT {fields} FROM {table}{limit}{offset}');

		return $this->db->query($q);
	}

    protected function get_orderby($table, $fields = '*', $orderby = false) {

        is_array($fields) AND $fields = implode(', ', $fields);

        $q = str_replace(['{fields}', '{table}', '{orderby}'], [
            $fields,
			$table,
            !empty($orderby) ? ' ORDER BY '.$orderby : '',
		], 'SELECT {fields} FROM {table} {orderby}');

		return $this->db->query($q);
    }

    protected function get_where_orderby($table, $where, $fields = '*', $orderby = false) {

        is_array($fields) AND $fields = implode(', ', $fields);

        $q = str_replace(['{fields}', '{table}', '{where}', '{orderby}'], [
            $fields,
			$table,
			$where,
            !empty($orderby) ? ' ORDER BY '.$orderby : '',
		], 'SELECT {fields} FROM {table} WHERE {where}{orderby}');

		return $this->db->query($q);
    }

    protected function escape($value) {

        return "'".$this->db->escape($value)."'";
    }

    protected function fetch_column($q, $column, $primary = false) {

        $q = $this->db->post($q);
        
        if($q->num_rows){
            $resultset = [];
            foreach($q as $row){
                if(!empty($primary) AND array_key_exists($primary, $row)){
                    $resultset[$row[$primary]] = $row[$column];
                } else {
                    $resultset[] = $row[$column];
                }
            }

            return $resultset;
        }

        return false;
    }

    protected function exists($table, $field, $value) {

        $q = $this->total($table, $field . ' = ' . $this->escape($value));

        return boolval(($q['total'] > 0));
    }

    protected function truncate($table) {

        return $this->db->post('TRUNCATE '.$table.';');
    }

    protected function replace($replace, $query) {

        return str_replace(array_keys($replace), array_values($replace), $query);
    }
}

/* END CLASS: SimpleModel */