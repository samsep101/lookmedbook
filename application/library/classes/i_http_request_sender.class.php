<?php
	interface IHttpRequestSender
	{

		public function setUrl($url);

		public function setParams($params);

		public function sendGetRequest();

		public function sendPostRequest();

		public function setHeaders($headers);

		public function sendPutRequest();

		public function sendDeleteRequest();

		public function getResponseHeaders();

		public function getResponseStatusCode();

		public function getResponse();

		public function setHeaderParser(IHttpHeaderParser $header_parser);

	}