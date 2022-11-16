<?php
	class ProductInformationManager extends ModelManager
	{
		protected $table_name = "product_information";
		protected $model_name = "ProductInformationModel";

		public function beforeSave(DynamicModel $product_information)
		{
			/**
			 * @var ProductInformationModel $product_information
			 */
			if(!$product_information->date_of_close_registration)
			{
				$product_information->date_of_close_registration = null;
			}

			if(!$product_information->date_registration)
			{
				$product_information->date_registration = null;
			}
		}

		/**
		 * @var int $product_id
		 * @return ProductInformationModel
		 */
		public function getOneByProductId($product_id)
		{
			$data = $this->orm_model->select()->where('product_id = ?', $product_id)->fetchOne();
			return $this->initOne($data);
		}

		/**
		 * @var int $is_non_presciption
		 * @return ProductInformationModel[]
		 */
		public function getListByIsNonPresciption($is_non_presciption)
		{
			$data = $this->orm_model->select()->where('is_non_presciption = ?', $is_non_presciption)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $is_used_during_pregnancy
		 * @return ProductInformationModel[]
		 */
		public function getListByIsUsedDuringPregnancy($is_used_during_pregnancy)
		{
			$data = $this->orm_model->select()->where('is_used_during_pregnancy = ?', $is_used_during_pregnancy)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $is_used_while_breastfeeding
		 * @return ProductInformationModel[]
		 */
		public function getListByIsUsedWhileBreastfeeding($is_used_while_breastfeeding)
		{
			$data = $this->orm_model->select()->where('is_used_while_breastfeeding = ?', $is_used_while_breastfeeding)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $is_used_in_violation_of_the_liver
		 * @return ProductInformationModel[]
		 */
		public function getListByIsUsedInViolationOfTheLiver($is_used_in_violation_of_the_liver)
		{
			$data = $this->orm_model->select()->where('is_used_in_violation_of_the_liver = ?', $is_used_in_violation_of_the_liver)->fetchAll();
			return $this->initList($data);
		}

	}