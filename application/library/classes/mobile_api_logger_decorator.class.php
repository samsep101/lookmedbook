<?php
	class MobileApiLoggerDecorator implements IDecorator
	{
		private $decorated_controller;



		public function setDecoratedObject($object)
		{
			$this->decorated_controller =  $object;
		}


		public function __call($method_name, $params)
		{
			$callback = array(
				$this->decorated_controller,
				$method_name
			);

			$file = $this->getFileDescriptor();
			$str = date('Y-m-d H:i:s')."\t".$_SERVER['REQUEST_URI']."\r\n";

			fwrite($file, $str);

			fclose($file);

			if(is_callable($callback))
			{
				$data = call_user_func_array($callback, $params);

				return $data;
			}
		}

		private function getFileDescriptor()
		{
			$filename = $this->getLogFileName();

			$f = fopen($filename, 'a');
			return $f;
		}

		private function getLogFileName()
		{
			return $this->log_file_path.date('Ymd').'.log';
		}

	}