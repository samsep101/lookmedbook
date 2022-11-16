<?php
	class HttpHeaderParser implements IHttpHeaderParser
	{
		/**
		 * @param string $response
		 *
		 * @return array
		 */
		public function getHeaders($response)
		{
			$data = explode("\r\n", $response);
			unset($data[0]);

			$result = array();

			if($data)
			{
				foreach($data as $v)
				{
					if(preg_match('/^([^:]+):(.+)$/ims', $v, $matches))
					{
						$result[trim($matches[1])] = trim($matches[2]);
					}
				}
			}

			return $result;
		}

		public function getStatusCode($response)
		{
			$data = explode("\r\n", $response);

			if ($data)
			{
				$code = $data[0];

				if(preg_match('/^HTTP[^ ]+ ([0-9]+) /ims', $code, $matches))
				{
					return $matches[1];
				}
			}

			return 200;
		}

	}