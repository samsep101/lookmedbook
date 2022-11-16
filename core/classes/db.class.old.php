<?php
    class Db
    {
        private $error = '';
        private $connection = '';
        private $query = '';

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

		    if (($this->connection = mysql_connect($db_host, $db_user, $db_password, true)) === FALSE) {
			    throw new Exception('Couldn\'t connect to DB');
		    }
		    if (($this->selectDB($db_name)) === FALSE) {
			    throw new Exception('Couldn\'t select DB');
		    }

		    if (defined('DB_INIT'))
			    if (DB_INIT){
					    mysql_query(DB_INIT, $this->connection);
			    }
        }

        /**
         * Disconnect from DB
         */
        public function disconnect()
        {
            if (!mysql_close($this->connection)) {
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
            return mysql_select_db($db_name, $this->connection);
        }

        /**
         * Get error during last query
         * @return string
         */
        public function error()
        {
            return mysql_error($this->connection);
        }

        public function error_code()
        {
            return mysql_errno($this->connection);
        }

        /**
         * Get info about last query
         * @return array
         */
        public function queryInfo()
        {
            return mysql_info($this->connection);
        }

        /**
         * Execute some query without fetching data
         *
         * @param string $query
         *
         * @return resource
         */
        public function post($query, $params = NULL)
        {
            if (count(func_get_args()) > 2)
                $params = func_get_args();
            $query = $this->prepareQuery($query, $params);

            $profiler = Profiler::getInstance();

            $profiler->startTime('mysql');

		        $this->query = mysql_query($query, $this->connection);
            $profiler->stopTime('mysql', $query);

            if ($this->query === FALSE) {
                throw new Exception(mysql_error($this->connection) . "\r\n" . $query);
            }

            return $this->query;
        }

        /**
         * Execute query and fetch result
         *
         * @param string $query
         * @param string $params
         *
         * @return array
         */
        public function query($query, $params = NULL)
        {
            if (count(func_get_args()) > 2)
                $params = func_get_args();

            $query = $this->post($query, $params);


            $result = array();
            if (($this->query != NULL) && (!is_bool($this->query))) {
                while ($row = mysql_fetch_array($this->query, MYSQL_ASSOC)) {
                    $result[] = $row;
                }
            }
            return $result;
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
            return mysql_affected_rows($this->connection);
        }

        /**
         * Get number of recieved rows  during last query
         * @return int
         */
        public function getNumRows()
        {
            return mysql_num_rows($this->query);
        }

        /**
         * Get last insert ID
         * @return int
         */
        public function lastInsertId()
        {
            return mysql_insert_id($this->connection);
        }

        /**
         * Prepare query for executing
         * Replace all '?' in query by escaped values at funciton params
         *
         * @param string $query
         * @param array  $params
         *
         * @return string
         */
        public function prepareQuery($query, $params = NULL)
        {
            if (count(func_get_args()) > 2)
                $params = func_get_args();
            if (count($params) > 1) {
                unset($params[0]);
                $replace = array();
                foreach ($params as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $k=> $v)
                            $value[$k] = mysql_real_escape_string($v);
                        $value = "('" . join("', '", $value) . "')";
                    } else {
                        $value = "'" . mysql_real_escape_string($value) . "'";
                    }
                    $query = preg_replace('/\?/is', $value, $query, 1);
                }
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

    }
