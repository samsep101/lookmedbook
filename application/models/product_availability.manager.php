<?php
	class ProductAvailabilityManager extends ModelManager
	{
		protected $table_name = "product_availability";
		protected $model_name = "ProductAvailabilityModel";


		public function beforeSave(DynamicModel $model)
		{
			/**
			 * @var ProductAvailabilityModel $model
			 */
			$model->dt_actual = date('Y-m-d H:i:s');
		}

		public function afterSave(DynamicModel $model)
		{
			/**
			 * @var ProductAvailabilityModel $model
			 */
			if($model->quantity > 0)
			{
				if (!$model->product->is_active)
				{
					$model->product->is_active = 1;
					$model->product->save;
				}
			} else {
				if ($model->product->is_active)
				{
					$model->product->is_active = 0;
					$model->product->save();
				}
			}
		}

		/**
		 * @var int $product_id
		 * @return ProductAvailabilityModel[]
		 */
		public function getListByProductId($product_id)
		{
			$data = $this->orm_model->select()->where('product_id = ?', $product_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $supplier_id
		 * @return ProductAvailabilityModel[]
		 */
		public function getListBySupplierId($supplier_id)
		{
			$data = $this->orm_model->select()->where('supplier_id = ?', $supplier_id)->fetchAll();
			return $this->initList($data);
		}


		public function setUnAvailableByDtActual($dt_actual)
		{
			$sql = 'UPDATE product_availability
					SET quantity = 0
					WHERE dt_actual < "'.$this->db->escape($dt_actual).'"';

			$this->db->query($sql);

			/**
			 * @var ProductManager $product_manager
			 */
			$product_manager = ModelManagerFactory::getByName('product');
			$product_manager->updateActiveStatus();
		}

	}