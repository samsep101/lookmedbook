<?php
	/**
	 * @property int $id
	 * @property string $login
	 * @property string $password
	 * @property string $fio
	 * @property int $phone
	 * @property string $email
	 * @property int $is_super
	 * @property int $role_id
	 * @property RoleModel $role
	 *
	 * @property  $clinics
	 */
	class UserModel extends DynamicModel
	{
		public function _field_clinics()
		{
			if(!isset($this->clinics))
			{
				$clinic_manager = new ClinicManager();

				$clinic_search_params = new ClinicSearchParams();
                $clinic_search_params->is_region = null;
                $clinic_search_params->registry_user_id = $this->getId();

                $this->clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);
			}

			return $this->clinics;
		}
	}