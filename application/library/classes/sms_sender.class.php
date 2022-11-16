<?php
	class SmsSender
	{
		public function send($number, $code)
		{
			$api = SmsApiFactory::getSmsApi();
			$number = str_replace('+', '', $number);
			$api->sendMessage($number, 'Ваш код: ' . $code);
		}

		public static function sendMessage($number, $message)
		{
			$api = SmsApiFactory::getSmsApi();
			$number = str_replace('+', '', $number);
			$number = str_replace('-', '', $number);

			$message = strip_tags($message);
			$message = trim($message);

			return $api->sendMessage($number, $message);
		}
	}