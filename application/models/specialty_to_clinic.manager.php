<?php
	class SpecialtyToClinicManager extends ModelManager
	{
		protected $table_name = 'specialty_to_clinic';
		protected $model_name = 'SpecialtyToClinicModel';

		public function checkExistsBySpecialtyIdAndClinicId($specialty_id, $clinic_id)
		{
			$sql = 'SELECT COUNT(*) as `result`
                FROM ' . $this->table_name . '
                WHERE specialty_id = ' . (int)$specialty_id . '
                AND clinic_id = ' . (int)$clinic_id;

			$data = $this->db->query($sql);

			return (bool)$data[0]['result'];
		}

	/**
		 * @return SpecialtyToClinicModel[]
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
		 * @return SpecialtyToClinicModel[]
		 */
		public function getListBySpecialtyId($specialty_id)
		{
			$data = $this->orm_model->select()->where('specialty_id = ?', (int)$specialty_id)->fetchAll();
			return $this->initList($data);
		}

	/**
		 * @return SpecialtyToClinicModel
		 */
		public function getOneByClinicIdAndSpecialtyId($clinic_id, $specialty_id)
		{
			$data = $this->orm_model->select()->where('clinic_id = ? AND specialty_id = ?', $clinic_id, $specialty_id)->fetchOne();
			return $this->initOne($data);
		}

        /*
        public function getListByClinicIdAndSpecializationId($clinic_id, $specialization_id)
        {
            $sql = 'SELECT *
                    FROM '.$this->table_name.' s2c
                    INNER JOIN specialization_to_clinic'
        }*/
	}
