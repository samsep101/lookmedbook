<?php
	class PurposeOfVisitToSpecialtyManager extends ModelManager
	{
		protected $table_name = 'purpose_of_visit_to_specialty';
		protected $model_name = 'PurposeOfVisitToSpecialtyModel';


        /**
		 * @return PurposeOfVisitToSpecialtyModel[]
		 */
		public function getListByPurposeOfVisitId($purpose_of_visit_id)
		{
			$data = $this->orm_model->select()->where('purpose_of_visit_id = ?', $purpose_of_visit_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return PurposeOfVisitToSpecialtyModel[]
		 */
		public function getListBySpecialtyId($specialty_id)
		{
			$data = $this->orm_model->select()->where('specialty_id = ?', $specialty_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return PurposeOfVisitToSpecialtyModel
		 */
		public function getOneByPurposeOfVisitIdAndSpecialtyId($purpose_of_visit_id, $specialty_id)
		{
			$data = $this->orm_model->select()->where('purpose_of_visit_id = ? AND specialty_id = ?', (int)$purpose_of_visit_id, (int)$specialty_id)->fetchOne();
			return (count($data)) ? $this->initOne($data) : null;
		}

		/**
		 * @return PurposeOfVisitToSpecialtyModel
		 */
		public function getOneMainBySpecialtyId($specialty_id)
		{
			$data = $this->orm_model->select()->where('specialty_id = ? AND is_main = 1', $specialty_id)->fetchOne();
			return $this->initOne($data);
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

		/**
		 * @return PurposeOfVisitToSpecialtyModel
		 */
		public function getOneBySpecialtyIdAndPurposeOfVisitId($specialty_id, $purpose_of_visit_id)
		{
			$data = $this->orm_model->select()->where('specialty_id = ? AND purpose_of_visit_id = ?', $specialty_id, $purpose_of_visit_id)->fetchOne();

			return $this->initOne($data);
		}
	}
