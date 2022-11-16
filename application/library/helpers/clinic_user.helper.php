<?php
	class ClinicUserHelper
	{
		public static function getClinicIdByUserId($user_id)
		{
			$clinic_to_user_manager = new ClinicToUserManager();
			return $clinic_to_user_manager->getClinicIdByUserId($user_id);
		}
	}