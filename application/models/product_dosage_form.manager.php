<?php
	class ProductDosageFormManager extends ModelManager
	{
		protected $table_name = "product_dosage_form";
		protected $model_name = "ProductDosageFormModel";

		/**
		 * @param $name
		 *
		 * @return ProductDosageFormModel
		 */
		public function getOneByName($name)
		{
			$data =  $this->orm_model->select()->where('name = ?', $name)->fetchOne();
			return $this->initOne($data);
		}

	}