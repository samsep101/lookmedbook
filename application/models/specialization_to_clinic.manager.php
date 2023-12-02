<?php
	class SpecializationToClinicManager extends ModelManager
	{
		protected $table_name = 'specialization_to_clinic';
		protected $model_name = 'SpecializationToClinicModel';

        /**
		 * @return SpecializationToClinicModel[]
		 */
		public function getListBySpecializationId($specialization_id){
			$data = $this->orm_model->select()->where('specialization_id = ?', $specialization_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return SpecializationToClinicModel[]
		 */
		public function getListByClinicId($clinic_id){
			$data = $this->orm_model->select()->where('clinic_id = ?', $clinic_id)->fetchAll();
			return $this->initList($data);
		}

		public function getActiveListByClinicId($clinic_id)
		{
			$sql = 'SELECT 	s.id,
						s.name,
						' . (int)$clinic_id . ' clinic_id,
						IF(	(SELECT COUNT(*)
							FROM ' . $this->table_name . ' ms
							WHERE clinic_id = ' . $clinic_id . '
								AND ms.specialization_id = s.id) = 1, 1, 0) is_selected
				FROM specialization s
				ORDER BY s.name';

			$data = $this->db->query($sql);
			$this->clearRegister();
			return $this->initList($data);
		}

		public function deleteByClinicId($clinic_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
					WHERE clinic_id = ' . (int)$clinic_id;

			$this->db->query($sql);
		}

        /**
		 * @return SpecializationToClinicModel
		 */
		public function getOneBySpecializationIdAndClinicId($specialization_id, $clinic_id)
		{
			$data = $this->orm_model->select()->where('specialization_id = ? AND clinic_id = ?', $specialization_id, $clinic_id)->fetchOne();
			return ($data) ? $data : false;
		}

        /**
         * Получение похожих клиник округа по наибольшему совпадению направлений медицины клиники.
         * @param int $clinic_id
         * @param string $equal_ids
         * @return SpecializationToClinicModel[]
         */
        public function getListEqualOfDistrictByClinicId($clinic_id, $equal_ids)
        {
            $sql = 'SELECT s2c1.*
                    FROM specialization_to_clinic s2c
                    INNER JOIN specialization_to_clinic s2c1 ON s2c1.specialization_id = s2c.specialization_id AND s2c1.clinic_id != ' .(int)$clinic_id .'
                    INNER JOIN clinic c ON s2c1.clinic_id = c.id
                    INNER JOIN clinic c1 ON c1.id = ' .(int)$clinic_id .'
                    INNER JOIN region r ON c.region_id = r.id
                    INNER JOIN region r1 ON c1.region_id = r1.id
                    WHERE s2c.clinic_id = ' .(int)$clinic_id .'
                    AND r.district_id = r1.district_id
                    AND c.is_active = 1';

            if($equal_ids)
            {
                $sql .= ' AND s2c1.clinic_id NOT IN (' .$equal_ids .')';
            }

            $sql .= ' GROUP BY clinic_id
                    ORDER BY COUNT(s2c1.clinic_id) DESC, RAND()
                    LIMIT 50';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }
	}
