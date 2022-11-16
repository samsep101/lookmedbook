<?php
	class PurposeOfVisitToDoctorManager extends ModelManager
	{
		protected $table_name = 'purpose_of_visit_to_doctor';
		protected $model_name = 'PurposeOfVisitToDoctorModel';

        public function afterSave(PurposeOfVisitToDoctorModel $model)
        {
            /*
            if ($model->doctor_id && $model->clinic_id && $model->specialty_id && $model->purpose_of_visit_id) {
                $cache = Register::get('cache');
                $cache_id = 'doctor_card_'.$model->doctor_id.'_specialty_'.$model->specialty_id.'_purpose_'.$model->purpose_of_visit_id;
                $cache->remove($cache_id, 'doctor_card_block');
                $cache_id = 'doctor_card_'.$model->doctor_id.'_specialty_'.$model->specialty_id.'_clinic_'.$model->clinic_id.'_purpose_'.$model->purpose_of_visit_id;
                $cache->remove($cache_id, 'doctor_card_block');
            }
            */
        }


        /**
		 * @return PurposeOfVisitToDoctorModel
		 */
		public function getOneByPurposeOfVisitIdAndDoctorId($purpose_of_visit_id, $doctor_id)
		{
			$data = $this->orm_model->select()->where('purpose_of_visit_id = ? AND doctor_id = ?', (int)$purpose_of_visit_id, (int)$doctor_id)->fetchOne();
			return (count($data)) ? $this->initOne($data) : null;
		}

		/**
		 * @return PurposeOfVisitToDoctorModel[]
		 */
		public function getListByDoctorId($doctor_id)
		{
			$data = $this->orm_model->select()->where('doctor_id = ?', $doctor_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @return PurposeOfVisitToDoctorModel[]
		 */
		/**
		 * @return PurposeOfVisitToDoctorModel[]
		 */
		public function getListByDoctorIdAndClinicId($doctor_id, $clinic_id)
		{
			$data = $this->orm_model->select()->where('doctor_id = ? AND clinic_id = ?', $doctor_id, $clinic_id)->fetchAll();

			return $this->initList($data);
		}

		public function deleteByDoctorId($doctor_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
					WHERE doctor_id = ' . (int)$doctor_id;

			$this->db->query($sql);
		}

        /**
		 * @return PurposeOfVisitToDoctorModel[]
		 */
		public function getListBySpecialtyId($specialty_id)
		{
			$sql = 'SELECT DISTINCT pv.*
                    FROM purpose_of_visit pv
                    INNER JOIN purpose_of_visit_to_doctor pv2d ON pv2d.purpose_of_visit_id = pv.id
                    WHERE pv2d.specialty_id = ' . (int)$specialty_id . '
                    ORDER BY pv.name ASC';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

        /**
		 * @return PurposeOfVisitToDoctorModel[]
		 */
		/**
		 * @return PurposeOfVisitToDoctorModel[]
		 */
		public function getListByDoctorIdAndSpecialtyId($doctor_id, $specialty_id)
		{
			$data = $this->orm_model->select()->where('doctor_id = ? AND specialty_id = ?', $doctor_id, $specialty_id)->fetchAll();

			return $this->initList($data);
		}

		public function deleteByDoctorIdAndClinicId($doctor_id, $clinic_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
					WHERE doctor_id = ' . (int)$doctor_id . '
						AND clinic_id = ' . (int)$clinic_id;

			$this->db->query($sql);
		}

		public function getFirstVisitPriceByDoctorIdAndClinicIdAndSpecialtyIdAndPurposeOfVisitId($doctor_id, $clinic_id, $specialty_id, $purpose_of_visit_id)
		{
			$query = 'visit_price IS NOT NULL';
			if ($doctor_id) {
				$query .= ' AND doctor_id = ' . (int) $doctor_id;
			}
			if ($clinic_id) {
				$query .= ' AND clinic_id = ' . (int) $clinic_id;
			}
			if ($specialty_id) {
				$query .= ' AND specialty_id = ' . (int) $specialty_id;
			}
			if ($purpose_of_visit_id) {
				$query .= ' AND purpose_of_visit_id = ' . (int) $purpose_of_visit_id;
			}
			
			$data = $this->orm_model->select()->where($query)->order('visit_price')->fetchOne();
			//return $doctor_id;
			return ($data) ? $data['visit_price'] : false;
		}

		/**
		 * @return PurposeOfVisitToDoctorModel
		 */
		public function getOneByClinicIdAndDoctorIdAndSpecialtyIdAndPurposeOfVisitId($clinic_id, $doctor_id, $specialty_id, $purpose_of_visit_id)
		{
			$sql = 'SELECT *
					FROM purpose_of_visit_to_doctor pv2d
					WHERE clinic_id = ' . (int)$clinic_id . '
						AND doctor_id = ' . (int)$doctor_id . '
						AND specialty_id = ' . (int)$specialty_id . '
						AND purpose_of_visit_id = ' . (int)$purpose_of_visit_id;

			$data = $this->db->query($sql);

			return $data ? $this->initOne($data[0]) : null;
		}

		/**
		 * @return PurposeOfVisitToDoctorModel
		 */
		/**
		 * @return PurposeOfVisitToDoctorModel
		 */
		public function getOneByPurposeOfVisitIdAndDoctorIdAndClinicIdAndSpecialtyId($purpose_of_visit_id, $doctor_id, $clinic_id, $specialty_id)
		{
			$sql = 'SELECT *
					FROM purpose_of_visit_to_doctor
					WHERE purpose_of_visit_id = ' . (int)$purpose_of_visit_id . '
						AND doctor_id = ' . (int)$doctor_id . '
						AND clinic_id = ' . (int)$clinic_id . '
						AND specialty_id = ' . (int)$specialty_id;

			$data = $this->db->query($sql);
			return $this->initList($data);
		}

		public function deleteUnActiveMainPurposesBySpecialtyId($specialty_id)
		{
			$sql = 'DELETE FROM purpose_of_visit_to_doctor
					WHERE specialty_id = ' . (int)$specialty_id . '
						AND is_auto = 1
						AND NOT EXISTS (
							SELECT id
							FROM purpose_of_visit_to_specialty pv2s
							WHERE purpose_of_visit_id = purpose_of_visit_to_doctor.purpose_of_visit_id
								AND specialty_id = ' . (int)$specialty_id . '
								AND is_main = 1
						)';

			$this->db->query($sql);
		}

        /**
         * @return PurposeOfVisitToDoctorModel[]
         */
        public function getListByIsToDeleteAndDeleteDate($is_to_delete, $delete_date)
        {
            $sql = 'SELECT *
                    FROM '.$this->table_name.'
                    WHERE is_to_delete = '.$is_to_delete.'
                    AND delete_date <= "' . date('Y-m-d H:i:s', $delete_date).'"';
            $data = $this->db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }
	}
