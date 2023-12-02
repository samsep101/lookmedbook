<?php
	class DiseaseToDiseaseTagManager extends ModelManager
	{
		protected $table_name = 'disease_to_disease_tag';
		protected $model_name = 'DiseaseToDiseaseTagModel';

		public function checkExistsByDiseaseIdAndDiseaseTagId($disease_id, $disease_tag_id)
		{
			$sql = 'SELECT COUNT(*) as `result`
                    FROM disease_to_disease_tag
                    WHERE disease_id = ' . (int)$disease_id . '
                    AND disease_tag_id = ' . (int)$disease_tag_id;

			$data = $this->db->query($sql);
			return (bool)$data[0]['result'];
		}

        public function deleteByDiseaseId($diseaseId)
        {
            $this->orm_model->delete('disease_id = ' . (int)$diseaseId);
        }
	}