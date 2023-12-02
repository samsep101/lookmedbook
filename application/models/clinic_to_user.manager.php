<?php
	class ClinicToUserManager extends ModelManager
	{
		protected $table_name = 'clinic_to_user';
		protected $model_name = 'ClinicToUserModel';

		public function getClinicIdByUserId($user_id)
		{
			$data = $this->orm_model->select()->where('user_id = ?', $user_id)->fetchOne();
			return $data ? $data['clinic_id'] : false;
		}


		/**
		 * @return ClinicToUserModel[]
		 */
		public function getOneByClinicIdAndUserId($clinic_id, $user_id)
		{
			$data = $this->orm_model->select()->where('clinic_id = ? AND user_id = ?', $clinic_id, $user_id)->fetchOne();
			return $this->initOne($data);
		}

		/**
		 * @return ClinicToUserModel[]
		 */
		public function getListByUserId($user_id)
		{
			$data = $this->orm_model->select()->where('user_id = ?', $user_id)->fetchAll();
			return $this->initList($data);
		}

		public function deleteByUserIdAndClinicId($user_id, $clinic_id)
		{
			$sql = 'DELETE FROM clinic_to_user
					WHERE user_id = ' . (int)$user_id . '
						AND clinic_id = ' . (int)$clinic_id;
			$this->db->query($sql);
		}
	}
