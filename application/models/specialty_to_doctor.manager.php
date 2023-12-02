<?php
	class SpecialtyToDoctorManager extends ModelManager
	{
		protected $table_name = 'specialty_to_doctor';
		protected $model_name = 'SpecialtyToDoctorModel';


        /**
		 * @return SpecialtyToDoctorModel[]
		 */
		public function getListBySpecialtyId($specialty_id)
		{
			$data = $this->orm_model->select()->where('specialty_id = ?', $specialty_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return SpecialtyToDoctorModel[]
		 */
		public function getListByDoctorId($doctor_id)
		{
			$data = $this->orm_model->select()->where('doctor_id = ?', $doctor_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @return SpecialtyToDoctorModel[]
		 */
		/**
		 * @return SpecialtyToDoctorModel[]
		 */
		public function getListByDoctorIdAndClinicId($doctor_id, $clinic_id)
		{
			$data = $this->orm_model->select()->where('doctor_id = ? AND clinic_id = ?', $doctor_id, $clinic_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return SpecialtyToDoctorModel[]
		 */
		public function getListByDoctorCertificateId($doctor_certificate_id)
		{
			$data = $this->orm_model->select()->where('doctor_certificate_id = ?', $doctor_certificate_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return SpecialtyToDoctorModel[]
		 */
		public function getListByQualifyingCategoryId($qualifying_category_id)
		{
			$data = $this->orm_model->select()->where('qualifying_category_id = ?', $qualifying_category_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return SpecialtyToDoctorModel
		 */
		public function getOneByDoctorIdAndSpecialityId($doctor_id, $speciality_id)
		{
			$data = $this->orm_model->select()->where('doctor_id = ? AND specialty_id = ?', (int)$doctor_id, (int)$speciality_id)->fetchOne();
			return (count($data)) ? $this->initOne($data) : null;
		}

		public function deleteByDoctorId($doctor_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
					WHERE doctor_id = ' . (int)$doctor_id;

			$this->db->query($sql);
		}

		public function deleteByDoctorIdAndClinicId($doctor_id, $clinic_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
					WHERE doctor_id = ' . (int)$doctor_id . '
						AND clinic_id = ' . (int)$clinic_id;

			$this->db->query($sql);
		}

		public function deleteByClinicIdAndDoctorIdAndSpecialtyId($clinic_id, $doctor_id, $specialty_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
					WHERE specialty_id = ' . (int)$specialty_id . '
					AND doctor_id = ' . (int)$doctor_id . '
					AND clinic_id = ' . (int)$clinic_id;

			$this->db->query($sql);
		}

        /**
		 * @return SpecialtyToDoctorModel[]
		 */
		public function getListBySpecialtyName($specialty_name)
		{
			$sql = 'SELECT s2d.*, s.name as specialty_name
                    FROM specialty_to_doctor s2d
                    INNER JOIN specialty s ON s.id = s2d.specialty_id
                    WHERE s.name LIKE "' . $this->db->escape($specialty_name) . '"
                    ORDER BY s2d.doctor_id';

			$data = $this->db->query($sql);
			return ($data) ? $this->initList($data) : array();
		}
	}
