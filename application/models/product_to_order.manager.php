<?php
	class ProductToOrderManager extends ModelManager
	{
		protected $table_name = "product_to_order";
		protected $model_name = "ProductToOrderModel";


		/**
		 * @var int $order_id
		 * @return ProductToOrderModel[]
		 */
		public function getListByOrderId($order_id)
		{
			$data = $this->orm_model->select()->where('order_id = ?', $order_id)->fetchAll();
			return $this->initList($data);
		}

        public function deleteListByOrderId($order_id)
        {
            $sql = 'DELETE
                    FROM product_to_order
                    WHERE order_id = ' .(int)$order_id;

            $this->db->query($sql);
        }
	}