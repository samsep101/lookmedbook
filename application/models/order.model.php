<?php
	/**
	 * @property int $id
	 * @property int $account_id
	 * @property AccountModel $account
	 * @property string $system_code
	 * @property string $phone_number
	 * @property string $name
	 * @property string $email
	 * @property string $address
	 * @property string $comment
	 * @property int $shipping_type_id
	 * @property ShippingTypeModel $shipping_type
	 * @property string $shipping_cost
	 * @property int $payment_type_id
	 * @property PaymentTypeModel $payment_type
	 * @property int $discount
	 * @property string $total_cost
	 * @property datetime $dt_order
	 * @property int $order_status_id
	 * @property OrderStatusModel $order_status
	 * @property ProductModel[] $products
	 * @property ProductToOrderModel[] $products_info
	 */
	class OrderModel extends DynamicModel
	{
		public function __construct()
		{
			$this->setDefaultValue('discount', 0);
			$this->setDefaultValue('payment_type_id', PaymentTypeModel::CASH);
		}

		/**
		 * @return ProductModel[]
		 */
		protected function _field_products()
		{
			if(!isset($this->products))
			{
				/**
				 * @var ProductManager $product_manager
				 */
				$product_manager = ModelManagerFactory::getByName('product');
				$this->products = $product_manager->getListByOrderId($this->getId());
			}

			return $this->products;
		}

		public function _field_products_info()
		{
			/**
			 * @var ProductToOrderManager $product_to_order_manager
			 */
			$product_to_order_manager = ModelManagerFactory::getByName('product_to_order');
			$this->products_info = $product_to_order_manager->getListByOrderId($this->getId());

			return $this->products_info;
		}

		public function getPrevOrderStatusId()
		{
			return isset($this->params['order_status_id']) ? (int)$this->params['order_status_id'] : null;
		}

		public function getNewOrderStatusId()
		{
			return $this->order_status_id;
		}

		public function statusChanged()
		{
			return ($this->getPrevOrderStatusId() != $this->getNewOrderStatusId());
		}

	}