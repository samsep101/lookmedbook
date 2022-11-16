<?php
	class ProfilerFileLogger implements ILogger
	{
		private $log_files_dir;

		public function __construct($log_files_dir)
		{
			$this->log_files_dir = $log_files_dir;
		}

		public function log($log_data)
		{
			$f = $this->getFileDescriptor();

			$message  = "------------------------------------------------\r\n";
			$message .= "dt: ".date('Y-m-d H:i:s')."\r\n";
			$message .= "page: ".$log_data['page']."\r\n";
			$message .= "user_ip: ".$log_data['ip']."\r\n";
			$message .= "controller: ".$log_data['controller']."\r\n";
			$message .= "action: ".$log_data['action']."\r\n";
			$message .= "params: ".$log_data['params']."\r\n";
			$message .= "referer: ".$log_data['referer']."\r\n";
			$message .= "total_time: ".$log_data['total_time']."\r\n";
			$message .= "utime: ".$log_data['utime']."\r\n";
			$message .= "stime: ".$log_data['stime']."\r\n";
			$message .= "mysql_time: ".$log_data['mysql_time']."\r\n";
			$message .= "memcache_time: ".$log_data['memcache_time']."\r\n";
			$message .= "memcache_count_queries: ".$log_data['memcache_count_queries']."\r\n";
			$message .= "mysql_count_queries: ".$log_data['mysql_count_queries']."\r\n";
			$message .= "mysql_queries: ".$log_data['mysql_queries']."\r\n";
			$message .= "================================================\r\n";
			fwrite($f, $message);
			fclose($f);
 		}


		private function getFileDescriptor()
		{
			$file_path = $this->getFilePath();
			$f = fopen($file_path, 'a');

			return $f;
		}

		private function getFileName()
		{
			return 'log'.date('Y-m-d').'.txt';
		}

		private function getFilePath()
		{
			return $this->log_files_dir.$this->getFileName();
		}
	}