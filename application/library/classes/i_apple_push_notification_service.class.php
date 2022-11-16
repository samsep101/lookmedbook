<?php
	interface IApplePushNotificationService
	{
		public function send($device_token, ApplePushNotificationParams $params, $data = array());
		public function subscribe($event, $callback);

		/**
		 * @return ApplePushNotificationParams
		 */
		public function getNotificationParams();
		public function getToken();
		public function getData();
		public function setHost($host);
		public function setPort($port);
		public function setCertificate($certificate);
		public function setPassphrase($passphrase);
	}