<?php
	class Environment
	{
		protected static  $values = array();

		public static function get($key)
		{
			return (isset(self::$values[$key])) ? self::$values[$key] : null;
		}

		public static function set($key, $value)
		{
			self::$values[$key] = $value;
		}
	}