<?php
	class ProductCategoryDescriptionManager extends ModelManager
	{
		protected $table_name = "product_category_description";
		protected $model_name = "ProductCategoryDescriptionModel";


		/**
		 * @var int $product_category_id
		 * @return ProductCategoryDescriptionModel[]
		 */
		public function getOneByProductCategoryId($product_category_id)
		{
			$data = $this->orm_model->select()->where('product_category_id = ?', $product_category_id)->fetchOne();
			return $this->initOne($data);
		}

	}