<?php
	class ClinicEmailManager extends ModelManager
	{
		protected $table_name = 'clinic_email';
		protected $model_name = 'ClinicEmailModel';

		/**
		 * @return ClinicEmailModel[]
		 */
		public function getListByClinicId($clinic_id)
		{
			$data = $this->orm_model->select()->where('clinic_id = ?', $clinic_id)->fetchAll();
			return $this->initList($data);
		}

		public function deleteByClinicId($clinic_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
					WHERE clinic_id = ' . (int)$clinic_id;

			$this->db->query($sql);
		}
	}
