<?php
    class Profiler
    {
        private $times;

        private static $instance;

        private $ip;
        private $controller;
        private $referer;
        private $action;
        private $params;

        private $page;

		private $logger;

        private function __construct()
        {
            $this->subscribe();
        }

        /**
         * @param mixed $page
         */
        public function setPage($page)
        {
            $this->page = $page;
        }


		public function setLogger($logger)
		{
			$this->logger = $logger;
		}

        /**
         * @param mixed $action
         */
        public function setAction($action)
        {
            $this->action = $action;
        }

        /**
         * @param mixed $controller
         */
        public function setController($controller)
        {
            $this->controller = $controller;
        }

        /**
         * @param mixed $instance
         */
        public static function setInstance($instance)
        {
            self::$instance = $instance;
        }

        /**
         * @param mixed $ip
         */
        public function setIp($ip)
        {
            $this->ip = $ip;
        }

        /**
         * @param mixed $params
         */
        public function setParams($params)
        {
            $this->params = $params;
        }

        /**
         * @param mixed $referer
         */
        public function setReferer($referer)
        {
            $this->referer = $referer;
        }



        public function subscribe()
        {

        }

        public function startTime($point)
        {
			if(!PROFILE_ENABLE)
				return;

            if (!isset($this->times[$point]))
                $this->times[$point] = array(
                    'start' => 0,
                    'start_utime' => 0,
                    'start_stime' => 0,
                    'end' => 0,
                    'end_utime' => 0,
                    'end_stime' => 0,
                    'comment' => '',
                    'sum' => 0,
                    'sum_utime' => 0,
                    'sum_stime' => 0,
                    'cnt' => 0
                );

            if (function_exists('getrusage')) {
                $dat = getrusage();
                $this->times[$point]['start_utime'] =
                    $dat['ru_utime.tv_sec'] * 1e6 + $dat['ru_utime.tv_usec'];
                $this->times[$point]['start_stime'] =
                    $dat['ru_stime.tv_sec'] * 1e6 + $dat['ru_stime.tv_usec'];
            }

            $this->times[$point]['start'] = microtime(TRUE);

            $this->times[$point]['cnt']++;
        }

        public function stopTime($point, $comment = '')
        {
			if(!PROFILE_ENABLE)
				return;

            if (function_exists('getrusage'))
            {
                $dat = getrusage();

                $this->times[$point]['end_utime'] =
                    $dat['ru_utime.tv_sec'] * 1e6 + $dat['ru_utime.tv_usec'];
                $this->times[$point]['end_stime'] =
                    $dat['ru_stime.tv_sec'] * 1e6 + $dat['ru_stime.tv_usec'];
            }

            $this->times[$point]['end'] = microtime(TRUE);


            $time = $this->times[$point]['end'] - $this->times[$point]['start'];
            $utime = ($this->times[$point]['end_utime'] -
                    $this->times[$point]['start_utime']) / 1e6;
            $stime =  ($this->times[$point]['end_stime'] -
                    $this->times[$point]['start_stime']) / 1e6;

            if ($point == 'mysql')
            {
                $comment = preg_replace('/([\t\r\n]+)|([ ]{2,})/',' ', $comment);
                $comment .= "\r\n";
                $comment .= 'Time: '.$time.' utime: '.$utime.' stime: '.$stime."\r\n";
                $comment .= '-------------------------------------------------------------------------';
            }

            if (!$this->times[$point]['comment'])
                $this->times[$point]['comment'] .= "\r\n".'-------------------------------------------------------------------------'."\r\n";

            $this->times[$point]['comment'] .= $comment."\r\n";

            $this->times[$point]['sum'] += $time;
            $this->times[$point]['sum_utime'] += $utime;
            $this->times[$point]['sum_stime'] += $stime;

        }

        public function logdata()
        {
			if(!PROFILE_ENABLE)
				return;

            $data = array();
            $data['utime'] = (isset($this->times['page']['sum_utime'])) ? $this->times['page']['sum_utime'] : null;
            $data['stime'] = (isset($this->times['page']['sum_stime'])) ? $this->times['page']['sum_stime'] : null;
            $data['mysql_time'] = (isset($this->times['mysql']['sum'])) ? $this->times['mysql']['sum'] : null;
            $data['mysql_count_queries'] = (isset($this->times['mysql']['cnt'])) ? $this->times['mysql']['cnt'] : null;
            $data['memcache_count_queries'] = (isset($this->times['memcache']['cnt'])) ? $this->times['memcache']['cnt'] : null;
            $data['mysql_queries'] = (isset($this->times['mysql']['comment'])) ? $this->times['mysql']['comment'] : null;
            $data['memcache_time'] = (isset($this->times['memcache']['sum'])) ? $this->times['memcache']['sum'] : null;
            $data['total_time'] = (isset($this->times['page']['sum'])) ? $this->times['page']['sum'] : null;

            $data['page'] = $this->page;
            $data['controller'] = $this->controller;
            $data['action'] = $this->action;
            $data['params'] =  $this->params;
            $data['referer'] = $this->referer;
            $data['ip'] = $this->ip;


            $this->logger->log($data);
        }

        // Эта служебная функция реализует паттерн Singleton
        public static function getInstance()
        {
            if (!isset(self::$instance)) {
                self::$instance = new Profiler();
            }

            return self::$instance;
        }
    }