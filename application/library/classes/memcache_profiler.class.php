<?php
	class MemcacheProfiler implements  IDecorator
	{
		/**
		 * @var MemcacheFacade
		 */
		private $decorated_object;

		/**
		 * @var Profiler
		 */
		private $profiler;

		public function __construct()
		{
			$this->profiler = ProfilerFactory::getProfiler();
		}

		public function setDecoratedObject($object)
		{
			$this->decorated_object = $object;
		}

		public function __call($method_name, $params)
		{
			$callback = array($this->decorated_object, $method_name);

			$logger_methods = array(
				'get',
				'set',
				'update',
				'delete'
			);

			if(in_array($method_name, $logger_methods))
			{
				$this->profiler->startTime('memcache');
			}

			$result = null;
			if(is_callable($callback))
			{
				$result = call_user_func_array($callback, $params);
			}

			if(in_array($method_name, $logger_methods))
			{
				$this->profiler->stopTime('memcache');
			}

			return $result;
		}
	}