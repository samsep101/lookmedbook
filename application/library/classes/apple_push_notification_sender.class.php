<?php
    class ApplePushNotificationSender implements IApplePushNotificationService
    {
		protected $host = 'gateway.push.apple.com';
		protected $port = 2195;
		protected $certificate = './ck.pem';

		protected $passphrase = 'LookMedBook';

		/**
		 * @var ApplePushNotificationParams
		 */
		protected $notification_params = null;
		protected $token;
		protected $data;

		protected $apns;

		public function __construct()
		{


		}

		public function getApns()
		{
			if (!$this->apns)
			{
				$apnsHost = $this->host;
				$apnsPort = $this->port;
				$apnsCert = $this->certificate;

				$streamContext = stream_context_create();
				stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
				stream_context_set_option($streamContext, 'ssl', 'passphrase', $this->passphrase);

				$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);

				$this->apns = $apns;
			}

			return $this->apns;

		}

		protected $callbacks = array(
			'afterSend' => array(),
			'beforeSend' => array(),
			'bigPayloadSize' => array()
		);

		protected function bind($event)
		{
			if (isset($this->callbacks[$event]))
				foreach($this->callbacks[$event] as $e)
				{
					call_user_func_array($e, array($event));
				}
		}

		public function subscribe($event, $callback)
		{
			$this->callbacks[$event][] = $callback;
		}

		public function setCertificate($certificate)
		{
			$this->certificate = $certificate;
		}

		public function setHost($host)
		{
			$this->host = $host;
		}

		public function setPort($port)
		{
			$this->port = $port;
		}

		public function setPassphrase($passphrase)
		{
			$this->passphrase = $passphrase;
		}

        public function send($device_token, ApplePushNotificationParams $params, $data = array())
		{
			$this->token = $device_token;
			$this->notification_params =  $params;
			$this->data = $data;

			$this->bind('beforeSend');

            $deviceToken = $device_token;

            $payload['aps'] = array('alert' => $params->alert, 'sound' => $params->sound, 'badge' => $params->badge);
            $payload['data'] = $data;

            $output = json_encode($payload, JSON_UNESCAPED_UNICODE);

			if (mb_strlen($output, 'utf-8') > 255)
			{
				$this->bind('bigPayloadSize');
			}

            $apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $deviceToken)) . chr(0) . chr(strlen($output)) . $output;
            fwrite($this->getApns(), $apnsMessage);

			$this->bind('afterSave');
        }

		public function getNotificationParams()
		{
			return $this->notification_params;
		}

		public function getToken()
		{
			return $this->token;
		}

		public function getData()
		{
			return $this->data;
		}
    }