<?php
	class SpecialtySynonymManager extends ModelManager
	{
		protected $table_name = 'specialty_synonym';
		protected $model_name = 'SpecialtySynonymModel';

		public function beforeSave(DynamicModel $specialty_synonym)
		{
			$word_decline = WordDeclination::getInstance();

			if(!$specialty_synonym->plural_name)
			{
				$specialty_synonym->plural_name = $word_decline->toPlural($specialty_synonym->name);
			}
			if(!$specialty_synonym->dative_name)
			{
				$specialty_synonym->dative_name = $word_decline->toDative($specialty_synonym->name);
			}
			if(!$specialty_synonym->genitive_name)
			{
				$specialty_synonym->genitive_name = $word_decline->toGenitive($specialty_synonym->name);
			}

			//leading to lower case Specialization Name first letter
			if($specialty_synonym->name && $specialty_synonym->name != 'ЛФК')
			{
				$letter = mb_substr($specialty_synonym->name, 0, 1, 'utf-8');
				$specialty_synonym->name = str_replace($letter, mb_strtolower($letter, 'utf-8'), $specialty_synonym->name);
			}

			parent::beforeSave($specialty_synonym);
		}

        /**
		 * @return SpecialtySynonymModel[]
		 */
		public function getListBySpecialtyId($specialty_id){
			$data = $this->orm_model->select()->where('specialty_id = ?', $specialty_id)->fetchAll();
			return $this->initList($data);
		}

	}
