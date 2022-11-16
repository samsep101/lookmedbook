<?php
	class FastLogin {
		/**
		 * @var FastLoginHashManager
		 */
		private $manager;

		public function __construct()
		{
			$this->manager = ModelManagerFactory::getByName('fast_login_hash');
		}

		public function generateHash($account_id, $dt_expire = null, $max_usage_count = 1)
		{
			if (!$dt_expire)
			{
				$dt_expire = date('Y-m-d H:i:s', time() + 24*60*60);
			} else {
				$dt_expire = date('Y-m-d H:i:s', strtotime($dt_expire));
			}

			$hash = $this->getUniqueHash();

			$fast_login = new FastLoginHashModel();
			$fast_login->account_id = $account_id;
			$fast_login->dt_expire = $dt_expire;
			$fast_login->max_usage_count = $max_usage_count;
			$fast_login->hash = $hash;
			$fast_login->save();

			return $hash;
		}

		public function tryToLogin($hash)
		{
			/**
			 * @var FastLoginHashModel $hash_model
			 */
			$hash_model = $this->manager->getOneByHash($hash);

			if ($hash_model)
			{
				$result = true;

				if($hash_model->max_usage_count && ($hash_model->usage_count >= $hash_model->max_usage_count))
				{
					$result = false;
				}

				if (strtotime($hash_model->dt_expire) < time())
				{
					$result = false;
				}

				if(!$result)
				{
					$hash_model->getManager()->delete($hash_model);
				} else {
					$hash_model->usage_count++;
					$hash_model->save();
					Acc::logout();
					Acc::login($hash_model->account);
				}

				return $result;
			} else {
				return false;
			}
		}

		private function getUniqueHash()
		{
			$hash = StringGeneratorHelper::generate(40);

			while($row = $this->manager->getOneByHash($hash))
			{
				$hash = StringGeneratorHelper::generate(40);
			}

			return $hash;
		}
	}