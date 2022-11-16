<?php
	class PiluliDosageFormLookupManager extends ModelManager
	{
		protected $table_name = "piluli_dosage_form_lookup";
		protected $model_name = "PiluliDosageFormLookupModel";

		/**
		 * @var string $piluli_name
		 * @return PiluliDosageFormLookupModel[]
		 */
		public function getListByPiluliName($piluli_name)
		{
			$data = $this->orm_model->select()->where('piluli_name = ?', $piluli_name)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var string $vidal_name
		 * @return PiluliDosageFormLookupModel[]
		 */
		public function getListByVidalName($vidal_name)
		{
			$data = $this->orm_model->select()->where('vidal_name = ?', $vidal_name)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var string $piluli_name
		 * @return string[]
		 */
		public function getVidalNameListByPiluliName($piluli_name)
		{
			$data = $this->getListByPiluliName($piluli_name);

			$result = array();

			foreach($data as $dosage_form_lookup)
			{
				$result[] = $dosage_form_lookup->vidal_name;
			}

			return $result;
		}

		/**
		 * @param $piluli_name
		 * @param $vidal_name
		 *
		 * @return PiluliDosageFormLookupModel
		 */
		public function getOneByPiluliNameAndVidalName($piluli_name, $vidal_name)
		{
			$data = $this->orm_model->select()->where('piluli_name = ? AND vidal_name = ?', $piluli_name, $vidal_name)->fetchOne();
			return $this->initOne($data);
		}
	}