<?php
	class PiluliProductToProductCategoryManager extends ModelManager
	{
		protected $table_name = "piluli_product_to_product_category";
		protected $model_name = "PiluliProductToProductCategoryModel";

		/**
		 * @var int $product_id
		 * @return PiluliProductToProductCategoryModel[]
		 */
		public function getListByProductId($product_id)
		{
			$data = $this->orm_model->select()->where('product_id = ?', $product_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $service_product_code
		 * @return PiluliProductToProductCategoryModel
		 */
		public function getOneByServiceProductCode($service_product_code)
		{
			$data = $this->orm_model->select()->where('service_product_code = ?', $service_product_code)->fetchOne();
			return $this->initOne($data);
		}

		/**
		 * @var int $product_category_id
		 * @return PiluliProductToProductCategoryModel
		 */
		public function getListByProductCategoryId($product_category_id)
		{
			$data = $this->orm_model->select()->where('product_category_id = ?', $product_category_id)->fetchAll();
			return $this->initList($data);
		}

	}