<?php
	class SuitableSpecialtyManager extends ModelManager
	{

		protected $table_name = 'suitable_specialty';
		protected $model_name = 'SuitableSpecialtyModel';

        /**
		 * @return SuitableSpecialtyModel[]
		 */
		public function getListBySpecialtyId($specialty_id){
			$data = $this->orm_model->select()->where('specialty_id = ?', $specialty_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return SuitableSpecialtyModel[]
		 */
		public function getListBySuitableSpecialtyId($suitable_specialty_id){
			$data = $this->orm_model->select()->where('suitable_specialty_id = ?', $suitable_specialty_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return SuitableSpecialtyModel[]
		 */
		public function getListByPurposeOfVisitId($purpose_of_visit_id){
			$data = $this->orm_model->select()->where('purpose_of_visit_id = ?', $purpose_of_visit_id)->fetchAll();
			return $this->initList($data);
		}

	}
