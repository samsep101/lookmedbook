<?php
	define('SMSC_LOGIN', 'lookmedbook');
	define('SMSC_PASSWORD', 'blueowl');
	define('SMSC_FROM', 'LookMedBook');

	require_once(ABS_ROOT . '/core/funcs/smsc_api.php');

	class SmsCApi implements SmsApi
	{
		public function sendMessage($number, $message)
		{
			$number = preg_replace('/[^0-9]/ims', '', $number);
			return send_sms($number, $message);
		}

	}