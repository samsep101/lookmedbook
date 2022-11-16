<?php
	class PiluliOrderDataFormatHelper
	{
		/**
		 * @param OrderModel[] $orders
		 *
		 * @return array
		 */
		public static function format(array $orders)
		{
			/**
			 * @var OrderModel[] $orders
			 */
			$result = array();

			foreach($orders as $order)
			{
				$result[] = self::formatOne($order);
			}

			return $result;
		}

		/**
		 * @param OrderModel $order
		 *
		 * @return array
		 */
		public static function formatOne(OrderModel $order)
		{
			$result = array();
			$result['order_code'] = $order->system_code;
			$result['name'] = $order->name;
			$result['phone'] = $order->phone_number;
			$result['email'] = $order->email;
			$result['address'] = $order->address;
			$result['comment'] = $order->comment;
			$result['shipping_id'] = $order->shipping_type_id;
			$result['shipping_cost'] = (float)$order->shipping_cost;
			$result['payment_id'] = $order->payment_type_id;
			$result['discount'] = (float)$order->discount;
			$result['total_cost'] = (float)$order->total_cost;
			$result['order_time'] = strtotime($order->dt_order);

			$result['products'] = array();

			foreach($order->products_info as $product_info)
			{
				$result['products'][] = array(
					'code' => $product_info->product->service_code,
					'name' => $product_info->product->clean_name,
					'amount' => $product_info->amount,
					'price' => $product_info->price
				);
			}

			return $result;
		}
	}