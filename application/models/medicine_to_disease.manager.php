<?php

	class MedicineToDiseaseManager extends ModelManager
	{
		protected $table_name = 'medicine_to_disease';
		protected $model_name = 'MedicineToDiseaseModel';

		/**
		 * @param int $disease_id
		 *
		 * @return MedicineToDiseaseModel
		 */
		public function getOneByDiseaseId($disease_id)
		{
			$db = Register::get('db');

			$sql = 'SELECT *
                FROM medicine_to_disease
                WHERE disease_id = "' . (int)$disease_id . '"';

			$data = $db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}
	}