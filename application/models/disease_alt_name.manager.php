<?php
	class DiseaseAltNameManager extends ModelManager
	{
		protected $table_name = 'disease_alt_name';
		protected $model_name = 'DiseaseAltNameModel';

    /**
		 * @return DiseaseAltNameModel[]
		 */
		public function getListByDiseaseId($disease_id)
		{
			$data = $this->orm_model->select()->where('disease_id = ?', $disease_id)->fetchAll();
			return $this->initList($data);
		}

		public function deleteByDiseaseId($diseaseId)
        {
            $this->orm_model->delete('disease_id = ' . (int) $diseaseId);
        }
	}
