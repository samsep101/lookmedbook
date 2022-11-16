<?php
	class SpecializationSynonymManager extends ModelManager
	{
		protected $table_name = 'specialization_synonym';
		protected $model_name = 'SpecializationSynonymModel';

        public function beforeSave(DynamicModel $model)
		{
			//leading to lower case Specialization Name first letter
			if($model->name && $model->name != 'ЛФК')
			{
				$name_letter = mb_substr($model->name, 0, 1, 'utf-8');
				$model->name = str_replace($name_letter, mb_strtolower($name_letter, 'utf-8'), $model->name);
			}
		}

        /**
		 * @return SpecializationSynonymModel[]
		 */
		public function getListBySpecializationId($specialization_id){
			$data = $this->orm_model->select()->where('specialization_id = ?', $specialization_id)->fetchAll();
			return $this->initList($data);
		}
	}
