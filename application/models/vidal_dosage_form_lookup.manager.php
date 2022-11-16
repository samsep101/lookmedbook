<?php
	class VidalDosageFormLookupManager extends ModelManager
	{
		protected $table_name = "vidal_dosage_form_lookup";
		protected $model_name = "VidalDosageFormLookupModel";


		/**
		 * @var int $product_dosage_form_id
		 * @return VidalDosageFormLookupModel[]
		 */
		public function getListByProductDosageFormId($product_dosage_form_id)
		{
			$data = $this->orm_model->select()->where('product_dosage_form_id = ?', $product_dosage_form_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $piluli_dosage_form_name
		 * @return VidalDosageFormLookupModel[]
		 */
		public function getListByPiluliDosageFormName($piluli_dosage_form_name)
		{
			$data = $this->orm_model->select()->where('piluli_dosage_form_name = ?', $piluli_dosage_form_name)->fetchAll();
			return $this->initList($data);
		}

	}