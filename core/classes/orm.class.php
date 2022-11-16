<?php

class Orm
{
  /**
   * @var OrmCondition
   */
  protected $condition = null;
  protected $table = null;
  protected $extensions = null;
  protected $primaryKey = null;

  /**
   * @var Db
   */
  protected $db;

  public function __construct($table = null, $primaryKey = null)
  {
    $this->table = $table;
    $this->primaryKey = $primaryKey;
  }

  public function setDb($db)
  {
    $this->db = $db;
  }

  protected function getDb()
  {
    if (!$this->db) {
      $this->db = Register::get('db');
    }

    return $this->db;
  }

  /**
   * @return OrmCondition
   */
  public function select()
  {
    $this->condition = new OrmCondition($this, $this->table);
    $this->setDb($this->getDb());
    return $this->condition;
  }

  public function fetchOne()
  {
    $this->condition->limit(0, 1);
    $sql = $this->condition->selectSql();
    $db = $this->getDb();
    $rows = $db->query($sql);
    if (count($rows) > 0)
      return (array)$rows[0];
    return null;
  }

  public function fetchLast()
  {
    $sql = $this->condition->selectSql();
    $db = $this->getDb();
    $rows = $db->query($sql);
    if (count($rows) > 0)
      return $rows[count($rows) - 1];
    return null;
  }

  public function fetchSingle()
  {
    $sql = $this->condition->selectSql();
    $db = $this->getDb();
    $rows = $db->query($sql);
    if (count($rows) > 0) {
      $keys = array_keys($rows[0]);
      return $rows[0][$keys[0]];
    }
    return null;
  }

  public function fetchAll()
  {
    $sql = $this->condition->selectSql();
    $db = $this->getDb();
    //echo '!<!--'.$sql.'-->!';
    $rows = $db->query($sql);
    return $rows;
  }

  public function fetchCount()
  {
    $sql = 'SELECT FOUND_ROWS() as result';
    $db = $this->getDb();
    $data = $db->query($sql);

    return $data[0]['result'];
  }

  public function update($data, $cond = '1=1')
  {
    $db = $this->getDb();
    $cond = $this->prepareCondition($cond);
    $items = array();
    foreach ($data as $key => $value) {
      if ($value === null) {
        $items[] = "`{$key}` = NULL";
      } else {
        $items[] = "`{$key}` = '" . $db->escape($value) . "'";
      }
    }
    $sql = "UPDATE `{$this->table}` SET " . join(', ', $items) . " WHERE " . $cond;
    $db->post($sql);
  }

  public function insert($data, $delayed = false)
  {
    $db = $this->getDb();
    $values_string = '';
    foreach ($data as $key => $value) {
      if ($value !== null)
        $value = $db->escape($value);
      $data[$key] = $db->escape($value);
      if (($value === null) || ($value === '')) {
        $values_string .= ' NULL, ';
      } else {
        $values_string .= '"' . $value . '", ';
      }
    }


    $values_string = trim($values_string, ', ');
    $sql = "INSERT ";
    if ($delayed) {
      $sql .= ' DELAYED ';
    }
    $sql .= " INTO `{$this->table}` (`" . join('`, `', array_keys($data)) . "`) VALUES (" . $values_string . ")";


    $db->post($sql);

    return $this->db->lastInsertId();
  }

  public function delete($cond = '1=1')
  {
    $cond = $this->prepareCondition($cond);
    $sql = "DELETE FROM `{$this->table}` WHERE " . $cond;
    $db = $this->getDb();
    $db->post($sql);
  }

  public function attach($extensionName)
  {
    $className = ucfirst($extensionName) . 'Extension';
    $object = new $className($this);
    $this->extensions[] = $object;
  }

  public function callExtension($method, $param)
  {
    if (!empty($this->extensions)) {
      foreach ($this->extensions as $extension) {
        if (is_callable(array($extension, $method))) {
          if (!is_array($param))
            $param = array($param);
          return call_user_method_array($method, $extension, $param);
        }
      }
    }
    return false;
  }

  public function getCode()
  {
    $modelName = get_class($this);
    $modelCode = str_replace('Model', '', $modelName);
    $modelCode = strtolower($modelCode);
    return $modelCode;
  }

  public function getTable()
  {
    return $this->table;
  }

  public function getPrimaryKey()
  {
    return $this->primaryKey;
  }

  public function __call($methodName, $arguments)
  {
    if (($result = $this->callExtension($methodName, $arguments)) !== false) {
      return $result;
    } else {
      throw new Exception("Unknow method " . $methodName);
    }

  }

  private function prepareCondition($condition)
  {
    if (is_string($condition))
      return $condition;

    if (is_array($condition)) {
      $conditionTerms = array();
      foreach ($condition as $key => $value)
        $conditionTerms[] = "`{$key}` = '" . $this->getDb()->escape($value) . "'";
      $conditionSql = join(' AND ', $conditionTerms);
      return $conditionSql;
    }

    if (is_a($condition, 'OrmCondition')) {
      $conditionSql = $condition->getWhere(true);
      return $conditionSql;
    }

    return null;
  }
}
