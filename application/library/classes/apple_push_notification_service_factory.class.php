<?php
	class ApplePushNotificationServiceFactory
	{
		/**
		 * @return IApplePushNotificationService
		 */
		public static function getService()
		{
			$service = new ApplePushNotificationSender();

			$host = SettingsManager::get('apple_push_notification_service_host');
			$port = SettingsManager::get('apple_push_notification_service_port');
			$certificate = SettingsManager::get('apple_push_notification_service_certificate');

			$service->setCertificate($certificate);
			$service->setHost($host);
			$service->setPort($port);


			$logger = new ApplePushNotificationLogger($service);
			$callback = array($logger, 'log');
			$service->subscribe('afterSend', $callback);
			$service->subscribe('beforeSend', $callback);
			return $service;
		}
	}