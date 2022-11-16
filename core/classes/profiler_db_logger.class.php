<?php
    class ProfilerDbLogger
    {
        /**
         * @var Db
         */
        private $db;

        private static $instance;

        public function __construct()
        {
            $this->db = new Db(PROFILER_DB_HOST, PROFILER_DB_USER, PROFILER_DB_PASSWORD, PROFILER_DB_NAME);
        }

        public function log(array $data)
        {
            $table_name = 'performance_log_'.date('Y_m_d');

            $sql = 'INSERT DELAYED INTO '.$table_name.'
                    SET ip = "'.$this->db->escape($data['ip']).'",
                        page = "'.$this->db->escape($data['page']).'",
                        controller = "'.$this->db->escape($data['controller']).'",
                        action = "'.$this->db->escape($data['action']).'",
                        params = "'.$this->db->escape($data['params']).'",
                        utime = "'.(float)$this->db->escape($data['utime']).'",
                        stime = "'.(float)$this->db->escape($data['stime']).'",
                        mysql_time = "'.(float)$this->db->escape($data['mysql_time']).'",
                        memcache_time = "'.(float)$this->db->escape($data['memcache_time']).'",
                        mysql_count_queries = "'.(int)$this->db->escape($data['mysql_count_queries']).'",
                        memcache_count_queries = "'.(int)$this->db->escape($data['memcache_count_queries']).'",
                        mysql_queries = "'.$this->db->escape($data['mysql_queries']).'",
                        total_time = "'.(float)$this->db->escape($data['total_time']).'",
                        referer = "'.$this->db->escape($data['referer']).'"
                        ';

            try {
                $this->db->query($sql);
            } catch (Exception $e) {
                if ($this->db->error_code() == 1146)
                {
                    $this->createLogTable($table_name);
                    $this->log($data);
                }
            }

        }

        private function createLogTable($table_name)
        {
            $sql = 'CREATE TABLE '.$table_name.'
                    LIKE performance_log_template';

            $this->db->query($sql);
        }

        public static function getInstance()
        {
            if (!self::$instance)
            {
                self::$instance = new ProfilerDbLogger();
            }

            return self::$instance;
        }
    }