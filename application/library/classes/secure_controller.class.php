<?php
	class SecureController extends BaseController
	{
		public function __construct()
		{
			if (!debug && (!in_array(php_sapi_name(), array('cgi-fcgi', 'cli'))))
			{
				exit();
			}
		}
	}