<?php
	class PiluliOrderXmlBuilder
	{
		public function build($orders_data)
		{
			$xml = new DomDocument('1.0', 'utf-8');
			$orders = $xml->createElement('orders');
			$xml->appendChild($orders);

			foreach($orders_data as $order_data)
			{
				$order = $xml->createElement('order');

				$orders->appendChild($order);

				$order->appendChild($xml->createElement('order_code', $order_data['order_code']));
				$order->appendChild($xml->createElement('name', $order_data['name']));
				$order->appendChild($xml->createElement('phone', $order_data['phone']));
				$order->appendChild($xml->createElement('email', $order_data['email']));
				$order->appendChild($xml->createElement('address', $order_data['address']));
				$order->appendChild($xml->createElement('comment', $order_data['comment']));
				$order->appendChild($xml->createElement('shipping_id', $order_data['shipping_id']));
				$order->appendChild($xml->createElement('shipping_cost', $order_data['shipping_cost']));
				$order->appendChild($xml->createElement('payment_id', $order_data['payment_id']));
				$order->appendChild($xml->createElement('discount', $order_data['discount']));
				$order->appendChild($xml->createElement('total_cost', $order_data['total_cost']));
				$order->appendChild($xml->createElement('order_time', $order_data['order_time']));

				$products = $xml->createElement('products');
				$order->appendChild($products);

				foreach($order_data['products'] as $product)
				{
					$product_tag = $xml->createElement('product');
					$product_tag->appendChild($xml->createElement('code', $product['code']));
					$product_tag->appendChild($xml->createElement('name', $product['name']));
					$product_tag->appendChild($xml->createElement('amount', $product['amount']));
					$product_tag->appendChild($xml->createElement('price', $product['price']));

					$products->appendChild($product_tag);
				}
			}


			return $xml->saveXML();
		}
	}