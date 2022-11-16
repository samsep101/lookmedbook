<?php
	class ShopContentmanagerLogManager extends ModelManager
	{
		protected $table_name = "shop_contentmanager_log";
		protected $model_name = "ShopContentmanagerLogModel";

		protected $insert_delayed = true;

		public function beforeSave(DynamicModel $model)
		{
			/**
			 *
			 */
		}

		/**
		 * @var int $user_id
		 * @return ShopContentmanagerLogModel[]
		 */
		public function getListByUserId($user_id)
		{
			$data = $this->orm_model->select()->where('user_id = ?', $user_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $product_id
		 * @return ShopContentmanagerLogModel[]
		 */
		public function getListByProductId($product_id)
		{
			$data = $this->orm_model->select()->where('product_id = ?', $product_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $date
		 * @return ShopContentmanagerLogModel[]
		 */
		public function getListByDate($date)
		{
			$data = $this->orm_model->select()->where('date = ?', $date)->fetchAll();
			return $this->initList($data);
		}

		public function checkIsStatusChangedByProductId($product_id)
		{
			$sql = 'SELECT COUNT(*) as cnt
					FROM '.$this->table_name.'
					WHERE product_id = '.(int)$product_id.'
						AND is_status_confirmed = 1';

			$data = $this->db->query($sql);

			return (bool)$data[0]['cnt'];
		}

	}