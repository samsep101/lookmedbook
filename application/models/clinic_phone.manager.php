<?php
	class ClinicPhoneManager extends ModelManager
	{
		protected $model_name = 'ClinicPhoneModel';
		protected $table_name = 'clinic_phone';



		public function beforeSave(ClinicPhoneModel $model)
		{
			parent::beforeSave($model);
			$model->phone_number = preg_replace('/[^0-9]/', '', $model->phone_number);
		}


        /**
		 * @return ClinicPhoneModel[]
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

        /**
		 * @return ClinicPhoneModel
		 */
		public function getOneByClinicId($clinic_id)
		{
			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE clinic_id = ' . $clinic_id;
			$db = Register::get('db');
			$data = $db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}
	}
