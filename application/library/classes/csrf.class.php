<?php
	class Csrf
	{
		/**
		 * @var MemcacheFacade
		 */
		private $memcache;

		public function __construct()
		{
			$this->memcache = MemcacheFacadeFactory::getService();
		}

		public function setToken()
		{
			if((!isset($_SESSION['csrf']['token']))
				|| ((int)$_SESSION['csrf']['expire'] < time())
				)
			{
				$token = StringGeneratorHelper::generate(40);
				$csrf_info = array(
					'token' => $token,
					'expire' => strtotime('30 minutes'),
					'user-agent' => empty($_SERVER['HTTP_USER_AGENT'])?'':$_SERVER['HTTP_USER_AGENT'],
					'ip' => empty($_SERVER['REMOTE_ADDR'])?'':$_SERVER['REMOTE_ADDR'],
				);

				$_SESSION['csrf'] = $csrf_info;

				$data = array(
					'token' => $token,
					'user-agent' => empty($_SERVER['HTTP_USER_AGENT'])?'':$_SERVER['HTTP_USER_AGENT'],
					'ip' => empty($_SERVER['REMOTE_ADDR'])?'':$_SERVER['REMOTE_ADDR'],
				);

				$this->memcache->set('csrf-token:'.session_id(), $data, array('session'), time() + 60*60);
			}
		}

		public function checkToken($token)
		{
			$data = $this->getSavedUserData();
			return ($data['token'] == $token) && ($data['user-agent'] == $_SERVER['HTTP_USER_AGENT']);
		}

		public function getUserToken()
		{
			$this->setToken();
			return $_SESSION['csrf']['token'];
		}

		public function getSavedUserData()
		{
//			if (!$this->memcache->get('csrf-token:'.session_id())) {
//				unset($_SESSION['csrf']);
//				$this->setToken();
//			}

			return $_SESSION['csrf'];//$this->memcache->get('csrf-token:'.session_id());
		}

		public function checkReferer()
		{
			$site_url = LinkHelper::getDomain();
			$site_url = str_replace('.', '\.', $site_url);
			$site_url = str_replace('-', '\-', $site_url);
			if (!preg_match('/http:\/\/([A-Za-z\-]+\.)?' . $site_url . '/', $_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']) {
				return false;
			}
			return true;
		}

	}