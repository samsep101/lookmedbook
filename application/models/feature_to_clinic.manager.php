<?php
	class FeatureToClinicManager extends ModelManager
	{
		protected $table_name = 'feature_to_clinic';
		protected $model_name = 'FeatureToClinicModel';


        /**
		 * @return FeatureToClinicModel[]
		 */
		public function getListByClinicId($clinic_id){
			$data = $this->orm_model->select()->where('clinic_id = ?', $clinic_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return FeatureToClinicModel[]
		 */
		public function getListByFeatureId($feature_id){
			$data = $this->orm_model->select()->where('feature_id = ?', $feature_id)->fetchAll();
			return $this->initList($data);
		}

		public function deleteByClinicId($clinic_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
					WHERE clinic_id = ' . (int)$clinic_id;

			$this->db->query($sql);
		}

	}
