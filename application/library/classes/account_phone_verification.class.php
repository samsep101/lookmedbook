<?php
	class AccountPhoneVerification
	{
		/**
		 * @var AccountPhoneCheckManager
		 */
		private $manager;

		public function __construct()
		{
			$this->manager = ModelManagerFactory::getByName('account_phone_check');
		}


		public function createCode($phone_number, $phone_id = null)
		{
			$phone_number = StringHelper::leaveOnlyTheNumber($phone_number);

			if($phone_id)
			{
				/**
				 * @var AccountPhoneManager $account_phone_manager
				 * @var AccountPhoneModel $account_phone
				 */
				$account_phone_manager = ModelManagerFactory::getByName('account_phone');
				$account_phone = $account_phone_manager->getOneById($phone_id);

				if(!$phone_number)
				{
					$phone_number = $account_phone->phone;
				}
				if(!$account_phone || ($account_phone->phone != $phone_number))
				{
					return null;
				}


			}

			if(!debug)
			{
				$code = StringGeneratorHelper::generateNumbers(4);
			} else {
				$code = 5555;
			}

			$account_phone_check = new AccountPhoneCheckModel();
			$account_phone_check->code = $code;
			$account_phone_check->phone_number = $phone_number;
			$account_phone_check->dt_actual = date('Y-m-d H:i:s', time() + 3 * 24 * 60 * 60);
			$account_phone_check->is_activated = 0;
			$account_phone_check->account_phone_id = $phone_id;
			$account_phone_check->save();

			$sms_sender = new SmsSender();
			$sms_sender->send('+' . StringHelper::leaveOnlyTheNumber($phone_number), $code);

			return $account_phone_check->getId();
		}

		public function checkCode($check_id, $confirm_code)
		{
			/**
			 * @var AccountPhoneCheckManager $account_phone_check
			 * @var AccountPhoneCheckModel $account_check
			 */
			$account_phone_check = ModelManagerFactory::getByName('account_phone_check');
			$account_check = $account_phone_check->getOneById($check_id);

			$result = false;

			if($account_check)
			{
				if($account_check->code == $confirm_code)
				{
					$result = true;
					$account_check->is_activated = 1;
					$account_check->save();

					if($account_check->account_phone && !$account_check->account_phone->is_confirmed)
					{
						$account_check->account_phone->is_confirmed = 1;
						$account_check->account_phone->save();
					}
				}
			}

			return $result;
		}

		public function checkStatusByCheckIdAndPhoneNumber($check_id, $phone_number)
		{
            $phone_number = StringHelper::leaveOnlyTheNumber($phone_number);
			$phone_check = $this->manager->getOneByIdAndPhoneNumber($check_id, $phone_number);

			if($phone_check && $phone_check->is_activated)
			{
				$phone_check->delete();
				return true;
			}
			else
			{
				return false;
			}
		}

	}