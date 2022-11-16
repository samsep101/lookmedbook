<?php
	interface IProductSupplierService
	{
		/**
		 * @return array
		 */
		public function getPriceList();

		/**
		 * @return int
		 */
		public function createOrder($order_data);

		/**
		 * @return array
		 */
		public function getOrdersStatuses();

		/**
		 * @param string $order_code
		 *
		 * @return int
		 */
		public function getOrderStatus($order_code);

		/**
		 * @param $order_code
		 *
		 * @return bool
		 */
		public function cancelOrder($order_code);
	}