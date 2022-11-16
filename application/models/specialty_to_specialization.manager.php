<?php
	class SpecialtyToSpecializationManager extends ModelManager
	{
		protected $table_name = 'specialty_to_specialization';
		protected $model_name = 'SpecialtyToSpecializationModel';

        /**
		 * @return SpecialtyToSpecializationModel[]
		 */
		public function getListBySpecializationId($specialization_id){
			$data = $this->orm_model->select()->where('specialization_id = ?', $specialization_id)->order('is_main DESC, sort ASC')->fetchAll();
			return $this->initList($data);
		}
                                                        
        /**
		 * @return SpecialtyToSpecializationModel[]
		 */
		public function getListBySpecialtyId($specialty_id){
			$data = $this->orm_model->select()->where('specialty_id = ?', $specialty_id)->order('is_main DESC, sort ASC')->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @return SpecialtyToSpecializationModel
		 */
		public function getOneMainBySpecialtyId($specialty_id)
		{
			$data = $this->orm_model->select()->where('specialty_id = ? AND is_main = 1', $specialty_id)->fetchOne();
			return $this->initOne($data);
		}

		/**
		 * @return SpecialtyToSpecializationModel
		 */
		public function getOneBySpecializationIdAndSpecialtyId($specialization_id, $specialty_id)
		{
			$data = $this->orm_model->select()->where('specialization_id = ? AND specialty_id = ?', $specialization_id, $specialty_id)->fetchOne();

			return $this->initOne($data);
		}

		public function setMainBySpecializationIdAndSpecialtyId($specialization_id, $specialty_id)
		{
			$sql = 'UPDATE specialty_to_specialization
					SET is_main = 0
					WHERE specialization_id = ' . (int)$specialization_id;
			$this->db->query($sql);

			$sql = 'UPDATE specialty_to_specialization
					SET is_main = 1
					WHERE specialization_id = ' . (int)$specialization_id . '
						AND specialty_id = ' . (int)$specialty_id;

			$this->db->query($sql);
		}

		public function setOrder($order)
		{
			$sql = 'SET @i = 0';
			$this->db->query($sql);

			$sql = 'UPDATE ' . $this->table_name . '
					SET `sort` = (@i:=@i+10)
					WHERE id IN (' . join(', ', $order) . ')
					ORDER BY FIELD(id, ' . join(', ', $order) . ')';
			$this->db->query($sql);
		}
	}
