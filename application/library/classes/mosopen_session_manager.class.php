<?php
	class MosopenSessionManager
	{
		protected static $instance;

		public static function getInstance()
		{
			if (!self::$instance)
			{
				self::$instance = new CurlSessionManager(ABS_ROOT . '/media/cookies/mosopen.cookie');
			}

			return self::$instance;
		}
	}