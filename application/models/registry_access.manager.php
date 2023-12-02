<?php
	class RegistryAccessManager
	{
		public function checkAccessToDoctorByManagerUserIdAndDoctorId($user_id, $doctor_id)
		{
			$sql = 'SELECT COUNT(*) as `count`
					FROM doctor d
					INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = d.id
					INNER JOIN clinic c ON d2c.clinic_id = c.id
					INNER JOIN clinic_to_user c2u ON c2u.clinic_id = c.id
					WHERE d.id = ' . (int)$doctor_id . '
						AND c2u.user_id = ' . (int)$user_id;

			$db = Register::get('db');

			$data = $db->query($sql);

			return $data[0]['count'];
		}

		public function checkAccessToUserByUserIdAndManagerUserId($user_id, $manager_user_id)
		{
			$sql = 'SELECT COUNT(*) as `count`
					FROM user u
					INNER JOIN clinic_to_user c2u ON c2u.user_id = u.id
					WHERE u.id = ' . (int)$user_id . '
						AND c2u.clinic_id IN (
							SELECT clinic_id
							FROM clinic_to_user c2u_2
							WHERE c2u_2.user_id = ' . (int)$manager_user_id . '
						)';

			$db = Register::get('db');

			$data = $db->query($sql);

			return (bool)$data[0]['count'];
		}
	}