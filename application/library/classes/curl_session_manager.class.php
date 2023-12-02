<?php
	class CurlSessionManager
	{
		private $cookie_file_path = null;


		public function __construct($cookie_file_path)
		{
			$this->cookie_file_path = $cookie_file_path;
		}

		public function getRequest($url, array $params = array())
		{
			$ch = curl_init(); // инициализирует CURL-сессию
			if (!$ch) {
				die("Couldn't initialize a cURL handle");
			}
			/*устанавливает опции для CURL-трансфера/transfer.*/
			curl_setopt($ch, CURLOPT_URL, $url.'?'.join('&', $params));
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_file_path); //Из какого файла читать
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_file_path);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');

			$result = curl_exec($ch);// выполняет CURL-сессию.

			if (empty($result)) {
				// some kind of an error happened
				curl_close($ch); // close cURL handler
				die(curl_error($ch));
			} else {
				curl_close($ch);//закрывает CURL-сессию.
				return $result;
			}
		}

		public function postRequest($url, array $params = array())
		{
			$ch = curl_init(); // инициализирует CURL-сессию
			if (!$ch) {
				die("Couldn't initialize a cURL handle");
			}
			/*устанавливает опции для CURL-трансфера/transfer.*/
			$params_string = $this->getParamsString($params);

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_file_path); //Из какого файла читать
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_file_path);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string); // add POST fields
			$result = curl_exec($ch);// выполняет CURL-сессию.

			if (empty($result)) {
				// some kind of an error happened
				die(curl_error($ch));
				curl_close($ch); // close cURL handler
			} else {
				curl_close($ch);//закрывает CURL-сессию.
				return $result;
			}
		}

		private function getParamsString($params)
		{
			$str = '';

			if ($params)
			{
				foreach($params as $key => $value)
				{
					$str .= $key.'='.$value.'&';
				}
			}

			$str = trim($str, '&');

			return $str;
		}
	}