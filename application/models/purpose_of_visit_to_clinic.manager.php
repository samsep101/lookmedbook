<?php
	class PurposeOfVisitToClinicManager extends ModelManager
	{
		protected $table_name = 'purpose_of_visit_to_clinic';
		protected $model_name = 'PurposeOfVisitToClinicModel';

        /**
		 * @return PurposeOfVisitToClinicModel[]
		 */
		public function getListByPurposeOfVisitId($purpose_of_visit_id){
			$data = $this->orm_model->select()->where('purpose_of_visit_id = ?', $purpose_of_visit_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return PurposeOfVisitToClinicModel[]
		 */
		public function getListByClinicId($clinic_id){
			$data = $this->orm_model->select()->where('clinic_id = ?', $clinic_id)->fetchAll();
			return $this->initList($data);
		}

        /**
         * @param $clinic_id
         * @param $specialty_id
         * @return PurposeOfVisitToClinicModel[]
         */
        public function getListByClinicIdAndSpecialtyId($clinic_id, $specialty_id)
        {
            $sql = 'SELECT *
                    FROM `'.$this->table_name.'`
                    WHERE clinic_id = '.(int)$clinic_id.'
                        AND specialty_id = '.(int)$specialty_id;

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        /**
		 * @return PurposeOfVisitToClinicModel[]
		 */
		public function getListBySpecialtyId($specialty_id){
			$data = $this->orm_model->select()->where('specialty_id = ?', $specialty_id)->fetchAll();
			return $this->initList($data);
		}

		public function deleteByClinicId($clinic_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
				WHERE clinic_id = ' . (int)$clinic_id;

			$this->db->query($sql);
		}

        public function getOneByClinicIdAndSpecialtyIdAndPurposeOfVisitId($clinic_id, $specialty_id, $purpose_of_visit_id)
        {
            $sql = 'SELECT *
                    FROM `'.$this->table_name.'`
                    WHERE clinic_id = '.(int)$clinic_id.'
                        AND specialty_id = '.(int)$specialty_id.'
                        AND purpose_of_visit_id = '.(int)$purpose_of_visit_id;

            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : null;
        }

        public function getListOfPurposeOfVisitByClinicId($clinic_id)
        {
            $sql = 'SELECT DISTINCT p.*
                    FROM purpose_of_visit_to_clinic p2c
                    INNER JOIN purpose_of_visit p ON p.id = p2c.purpose_of_visit_id
                    WHERE p2c.clinic_id = ' .(int)$clinic_id;

            $data = $this->db->query($sql);

            return $this->initList($data);
        }
	}
