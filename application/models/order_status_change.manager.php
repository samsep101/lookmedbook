<?php
	class OrderStatusChangeManager extends ModelManager
	{
		protected $table_name = "order_status_change";
		protected $model_name = "OrderStatusChangeModel";

		public function beforeSave(DynamicModel $model)
		{
			if($model->isNew())
			{
				$model->dt = date('Y-m-d H:i:s');
			}
		}

		/**
		 * @var int $prev_order_status_id
		 * @return OrderStatusChangeModel[]
		 */
		public function getListByPrevOrderStatusId($prev_order_status_id)
		{
			$data = $this->orm_model->select()->where('prev_order_status_id = ?', $prev_order_status_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $new_order_status_id
		 * @return OrderStatusChangeModel[]
		 */
		public function getListByNewOrderStatusId($new_order_status_id)
		{
			$data = $this->orm_model->select()->where('new_order_status_id = ?', $new_order_status_id)->fetchAll();
			return $this->initList($data);
		}

	}