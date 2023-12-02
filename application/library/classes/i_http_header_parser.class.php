<?php
	interface IHttpHeaderParser
	{
		/**
		 * @param string $response
		 *
		 * @return array
		 */
		public function getHeaders($response);
	}