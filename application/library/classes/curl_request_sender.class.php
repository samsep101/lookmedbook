<?php
	class CurlRequestSender
	{
		public static function get($url)
		{
			$curl = new CurlRequest();
			$curl->setUrl($url);

			if($curl->sendGetRequest())
			{
				return $curl->getResponse();
			} else {
				return false;
			}
		}


		public static function post($url, $postfields)
		{
			$curl = new CurlRequest();
			$curl->setUrl($url);
			$curl->setParams($postfields);

			if($curl->sendPostRequest()){
				return $curl->getResponse();
			} else {
				return false;
			}
		}

		public static function getRedirectFollowLocation($url)
		{
			$curl = new CurlRequest();
			$curl->setUrl($url);
			$curl->sendGetRequest();

			$headers = $curl->getResponseHeaders();

			return isset($headers['Location']) ? $headers['Location'] : '';
		}
	}