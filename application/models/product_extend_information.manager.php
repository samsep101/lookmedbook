<?php
	class ProductExtendInformationManager extends ModelManager
	{
		protected $table_name = "product_extend_information";
		protected $model_name = "ProductExtendInformationModel";


		/**
		 * @var int $product_id
		 * @return ProductExtendInformationModel
		 */
		public function getOneByProductId($product_id)
		{
			$data = $this->orm_model->select()->where('product_id = ?', $product_id)->fetchOne();
			return $this->initOne($data);
		}

	}