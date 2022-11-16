<?php
	class SpecialtyToDiseaseManager extends ModelManager
	{
		protected $table_name = 'specialty_to_disease';
		protected $model_name = 'SpecialtyToDiseaseModel';

        /**
         * @return SpecialtyToDiseaseModel[]
         */
        public function getListByDiseaseId($disease_id)
        {
            $sql = 'SELECT ' . $this->selected_fields . '
                FROM ' . $this->table_name . '
                WHERE disease_id = ' . (int)$disease_id;
            $db = Register::get('db');
            $data = $db->query($sql);

            return $this->initList($data);
        }

    /**
		 * @return SpecialtyToDiseaseModel
		 */
		public function getOneByDiseaseIdAndMainFlag($disease_id)
		{
			$sql = 'SELECT ' . $this->selected_fields . '
                FROM ' . $this->table_name . '
                WHERE disease_id = ' . (int)$disease_id . '
                AND main_flag = 1';
			$db = Register::get('db');
			$data = $db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

    /**
		 * @return SpecialtyToDiseaseModel
		 */
		/**
		 * @return SpecialtyToDiseaseModel
		 */
		public function getOneByDiseaseIdAndMainFlagAndFlag($disease_id, $flag)
		{
			if(!in_array($flag, array('male', 'female', 'children', 'pregnant', 'adult', 'newborn')))
			{
				return array();
			}

			$flag = 'is_' . $flag;

			$sql = 'SELECT ' . $this->selected_fields . '
                FROM ' . $this->table_name . '
                WHERE disease_id = ' . (int)$disease_id . '
                AND main_flag = 1
                AND ' . $flag . ' = 1';
			$db = Register::get('db');
			$data = $db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

		public function deleteByDiseaseId($diseaseId)
        {
            $this->orm_model->delete('disease_id = ' . (int)$diseaseId);
        }
	}
