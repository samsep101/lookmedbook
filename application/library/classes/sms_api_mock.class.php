<?php
	class SmsApiMock implements SmsApi
	{
		public function sendMessage($number, $message)
		{
			return true;
		}

	}