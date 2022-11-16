<?php
	class DoctorCertificateManager extends ModelManager
	{
		protected $table_name = 'doctor_certificate';
		protected $model_name = 'DoctorCertificateModel';

        /**
		 * @return DoctorCertificateModel[]
		 */
		public function getListByDoctorId($doctor_id)
		{
			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE doctor_id = ' . (int)$doctor_id;

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

        /**
		 * @return DoctorCertificateModel[]
		 */
		public function getListBySpecialtyId($specialty_id){
			$data = $this->orm_model->select()->where('specialty_id = ?', $specialty_id)->fetchAll();
			return $this->initList($data);
		}

		public function deleteByDoctorId($doctor_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
                    WHERE  doctor_id=' . (int)$doctor_id;

			$this->db->query($sql);
		}

        public function getOneByDoctorId($doctor_id)
        {
            $sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE doctor_id = ' . (int)$doctor_id;

            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : null;
        }
	}
