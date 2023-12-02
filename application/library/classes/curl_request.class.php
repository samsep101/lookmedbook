<?php
	class CurlRequest implements IHttpRequestSender
	{
		private $headers = array();

		/**
		 * @var resource
		 */
		private $ch = null;

		private $response_headers = array();
		private $response_status_code = null;

		private $response;

		private $url;
		private $params;

		/**
		 * @var IHttpHeaderParser
		 */
		private $header_parser = null;

		public function __construct()
		{
			$this->initializeCurl();
			$this->header_parser = new HttpHeaderParser();
		}

		private function initializeCurl()
		{
			$this->ch = curl_init();
		}

		private function execCurl()
		{
			if(!$this->url)
			{
				throw new HttpRequestNotSetUrlException('Don\'t initialized param $url');
			}

			curl_setopt($this->ch, CURLOPT_URL, $this->url);
			curl_setopt($this->ch, CURLOPT_HEADER, 1);
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->headers);

			$data = curl_exec($this->ch);

			if(curl_errno($this->ch) == 0)
			{
				$header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
				$header = substr($data, 0, $header_size);

				$this->response_status_code = $this->header_parser->getStatusCode($header);
				$this->response_headers = $this->header_parser->getHeaders($header);

				$body = substr($data, $header_size);

				$this->response = $body;

				$this->resetOptions();
				return true;
			} else {
				return false;
			}
		}

		private function resetOptions()
		{
			$this->url = null;
			$this->params = array();
		}

		public function sendGetRequest()
		{
			return $this->execCurl();
		}

		public function sendPostRequest()
		{
			curl_setopt($this->ch, CURLOPT_POST, 1);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->params); // add POST fields

			return $this->execCurl();
		}

		public function sendDeleteRequest()
		{
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

			return $this->execCurl();
		}

		public function sendPutRequest()
		{
			curl_setopt($this->ch, CURLOPT_PUT, 1);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->params);

			return $this->execCurl();
		}

		public function setHeaders($headers)
		{
			$this->headers = $headers;
		}

		public function getResponseHeaders()
		{
			return $this->response_headers;
		}

		public function getResponseStatusCode()
		{
			return $this->response_status_code;
		}

		public function setHeaderParser(IHttpHeaderParser $header_parser)
		{
			$this->header_parser = $header_parser;
		}

		public function setUrl($url)
		{
			$this->url = $url;
		}

		public function setParams($params)
		{
			$this->params = $params;
		}

		public function getResponse()
		{
			return $this->response;
		}

		public function __destruct()
		{
			curl_close($this->ch);
		}
	}

	class HttpRequestNotSetUrlException extends Exception
	{

	}
