<?php
	class ClinicToUserValidator extends ModelValidator
	{
		public function validate(ClinicToUserModel $clinic_to_user)
		{
			if ($clinic_to_user->user->role_id == RoleModel::ACCOUNT_REGISTRY)
				{
					$clinic_to_user_manager = new ClinicToUserManager();
					$count = count($clinic_to_user_manager->getListByUserId($clinic_to_user->clinic_id, $clinic_to_user->user_id));

					if(!$clinic_to_user->getId())
					{
						$count++;
					}

					if($count > 1)
					{
						$this->error_messages[] = 'У представителя клиники не может быть больше 1 клиники';
						return false;
					}
				}

				return true;
			}

		}