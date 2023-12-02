<?php
	class ElasticaFactory
	{
		/**
		 * @var \Elastica\Client
		 */
		private static $instance = null;

		/**
		 * @return \Elastica\Client
		 */
		public static function getApi()
		{
			if(!self::$instance)
			{
				self::$instance = new \Elastica\Client(Register::get('ELASTICA_SERVERS'));
			}

			return self::$instance;
		}
	}