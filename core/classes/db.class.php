<?php

class Db
{
  private $error = '';
    /**
     * @var mysqli
     */
  private $connection = null;
  /** @var mysqli_result|bool */
  private $query;

  private $host;
  private $user;
  private $password;
  private $db_name;

  private $profile = true;

  private $connected = false;

  private static $subscribes = array();

  public function __construct($host = 'localhost', $user = null, $password = null, $db_name = null)
  {
    $this->host = $host;
    $this->user = $user;
    $this->password = $password;
    $this->db_name = $db_name;

    $this->connect();
  }

  public function disableProfile()
  {
    $this->profile = false;
  }

  public function destroy()
  {
    $this->disconnect();
  }

  /**
   * Connect to DB
   */
  public function connect()
  {
    $db_host = $this->host ? $this->host : DB_HOST;
    $db_user = $this->user ? $this->user : DB_USER;
    $db_password = $this->password ? $this->password : DB_PASSWORD;
    $db_name = $this->db_name ? $this->db_name : DB_NAME;

    
    if (($this->connection = mysqli_connect($db_host, $db_user, $db_password, $db_name)) === FALSE) {
      throw new Exception('Couldn\'t connect to DB');
    }

    if (defined('DB_INIT'))
      if (DB_INIT) {
        $this->connection->query(DB_INIT);
      }
  }

  /**
   * Disconnect from DB
   */
  public function disconnect()
  {
    if (!$this->connection->close()) {
      throw new Exception('Couldn\'t close connection');
    }
  }

  /**
   * Use specified DB
   *
   * @param string $db_name
   *
   * @return mixed
   */
  public function selectDB($db_name = '')
  {
    return $this->connection->select_db($db_name);
  }

  /**
   * Get error during last query
   * @return string
   */
  public function error()
  {
    return $this->connection->error;
  }

  public function error_code()
  {
    return $this->connection->errno;
  }

  /**
   * Get info about last query
   * @return array
   */
  public function queryInfo()
  {
    return $this->connection->info;
  }

  /**
   * Execute some query without fetching data
   *
   * @param string $query
   * @param array|null $params
   * @return mysqli_result
   * @throws Exception
   */
  public function post($query, $params = NULL)
  {
    if (count(func_get_args()) > 2) {
      $params = func_get_args();
      unset($params[0]);
    }
    $query = $this->prepareQuery($query, $params);

    $profiler = Profiler::getInstance();

    $profiler->startTime('mysql');

    (BENCHMARKS || debug > 2) AND benchmarks()->query_start($query);

    $this->query = $this->connection->query($query);
    $profiler->stopTime('mysql', $query);

    (BENCHMARKS || debug > 2) AND benchmarks()->query_stop($query);
    (BENCHMARKS || debug > 2) AND benchmarks()->count('Кол-во запросов');

    if ($this->query === FALSE) {
      throw new Exception($this->connection->error . "\r\n" . $query);
    }

    return $this->query;
  }


  /**
   * Execute query and fetch result
   *
   * @param string $query
   * @param array|null $params
   *
   * @return array
   */
  public function query($query, $params = NULL)
  {
    if (count(func_get_args()) > 2)
      $params = func_get_args();

    $this->post($query, $params);

    $result = array();
    if ($this->query instanceof mysqli_result) {
      while ($row = mysqli_fetch_array($this->query, MYSQLI_ASSOC)) {
        $result[] = $row;
      }
    }
    return $result;
  }

    /**
     * @param $query
     * @return mysqli_stmt
     */
  public function prepare($query)
  {
      return $this->connection->prepare($query);
  }

  /**
   * Execute query and fetch first row of result
   *
   * @param string $query
   * @param string $params
   *
   * @return array
   */
  public function get($query, $params = NULL)
  {
    if (count(func_get_args()) > 2)
      $params = func_get_args();
    $result = $this->query($query, $params);
    return isset($result[0]) ? $result[0] : NULL;
  }

  /**
   * Execute query and fetch first column in fisrt row of result
   *
   * @param string $query
   * @param string $params
   *
   * @return array
   */
  public function single($query, $params = NULL)
  {
    if (count(func_get_args()) > 2)
      $params = func_get_args();
    $aResult = $this->query($query, $params);
    if (empty($aResult[0]))
      return NULL;
    $keys = array_keys($aResult[0]);
    return $aResult[0][$keys[0]];
  }

  /**
   * Get next ID by autoicreament
   *
   * @param string $tableName
   *
   * @return int
   */
  public function getAutoIncrement($tableName = '')
  {
    $rows = $this->query("SHOW TABLE STATUS LIKE '$tableName'");
    return @$rows[0]['Auto_increment'];
  }

  /**
   * Get number of affected rows during last query
   * @return int
   */
  public function getAffectedRows()
  {
    return $this->connection->affected_rows;
  }

  /**
   * Get number of recieved rows  during last query
   * @return int
   */
  public function getNumRows()
  {
    return $this->connection->num_rows;
  }

  /**
   * Get last insert ID
   * @return int
   */
  public function lastInsertId()
  {
    return $this->connection->insert_id;
  }

  /**
   * Prepare query for executing
   * Replace all '?' in query by escaped values at funciton params
   *
   * @param string $query
   * @param array $params
   *
   * @return string
   */
  public function prepareQuery($query, $params = NULL)
  {
    if (!empty($params)) {
      $values = [];
      foreach ($params as $key => $value) {
        if (is_array($value)) {
          foreach ($value as $k => $v) {
            $value[$k] = $this->connection->real_escape_string($v);
          }
          $value = "('" . implode("', '", $value) . "')";
        } else {
          $value = "'" . $this->connection->real_escape_string($value) . "'";
        }
        $values[] = $value;
      }
      $parts = [];
      foreach (explode('?', $query) as $i => $part) {
        if ('' !== $part) {
          $parts[] = trim($part);
          $parts[] = array_shift($values);
        }
      }
      return implode(' ', $parts);
    }
    return $query;
  }

  public static function getCountWithoutLimit()
  {
    $db = Register::get('db');

    $data = $db->query('SELECT FOUND_ROWS() as value');

    return $data[0]['value'];
  }

  public function getTablesList()
  {
    $data = $this->query('SHOW TABLES');

    $result = array();
    foreach ($data as $k => $v) {
      foreach ($v as $v1) {
        $result[] = $v1;
      }
    }

    return $result;
  }

  public function getTableFields($table_name)
  {
    $data = $this->query('SHOW COLUMNS FROM `' . $table_name . '`');
    return $data;
  }

	public function escape($string) {
    return $this->connection->real_escape_string($string);
  }

  public function beginTransaction($isolationLevel=null)
  {
      return $this->connection->begin_transaction($isolationLevel);
  }

  public function commitTransaction()
  {
      return $this->connection->commit();
  }

  public function rollbackTransaction()
  {
      return $this->connection->rollback();
  }
}
