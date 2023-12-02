<?php
	class ApplePushNotificationLogger
	{
		private $log_file_path;

		/**
		 * @var IApplePushNotificationService
		 */
		private $object;

		public function __construct(IApplePushNotificationService $object)
		{
		    $this->log_file_path = ABS_ROOT . '/media/logs/push/';
			$this->setObject($object);
		}

		public function setObject(IApplePushNotificationService $object)
		{
			$this->object = $object;
		}

		public function log($event)
		{
			$params = $this->object->getNotificationParams();
			$f = $this->getFileDescriptor();

			if($event == 'bigPayloadSize')
			{
				$str = date('Y-m-d H:i:s')."\t".'большой размер payload token: '.$this->object->getToken().' message: '.$params->alert." sound: ".$params->sound."visitId ".$this->object->getData()['visitId']."\r\n";
			}
			else
			{
				$str = date('Y-m-d H:i:s')."\t".'token: '.$this->object->getToken().' message: '.$params->alert."visitId ".$this->object->getData()['visitId']."\r\n";
			}

			fwrite($f, $str);
			fclose($f);
		}

		private function getFileDescriptor()
		{
			$filename = $this->getLogFileName();

			$f = fopen($filename, 'a');
			return $f;
		}

		private function getLogFileName()
		{
			return $this->log_file_path.date('Ymd').'.log';
		}

	}