<?php
	/**
	 * @depends XMLReader
	 */
	class PiluliApi implements IProductSupplierService
	{
		/**
		 * @var IHttpRequestSender
		 */
		private $request_sender;

		private $login;
		private $password;

		/**
		 * @var PiluliProductNameParser
		 */
		private $name_parser;

		/**
		 * @var IXmlConverter
		 */
		private $xml_converter;

		public function __construct($login, $password)
		{
			$this->request_sender = new CurlRequest();
			$this->login = $login;
			$this->password = $password;

			$this->name_parser = new PiluliProductNameParser();
		}

		public function setXmlConverter(IXmlConverter $xml_converter)
		{
			$this->xml_converter = $xml_converter;
		}

		public function getXmlConverter()
		{
			if(!isset($this->xml_converter))
			{
				$this->xml_converter = new XmlConverter();
			}

			return $this->xml_converter;
		}

		/**
		 * @return SupplierProductInformation[]
		 * @throws Exception
		 */
		public function getPriceList()
		{
			ini_set('memory_limit', '192M');
			$url = $this->buildUrl() . '/exchange/price';
			$this->request_sender->setUrl($url);

			if($this->request_sender->sendGetRequest())
			{
				$xml = $this->request_sender->getResponse();
				$data = $this->convertPriceXML($xml);

				$collection = ObjectCollectionFactory::getCollection('supplier_product_information');
				foreach($data['products']['_c']['product'] as $k => &$v)
				{
					$product_info = array();
					$product_info['code'] = $v['_c']['code']['_v'];
					$product_info['manufacturer'] = $v['_c']['manufacturer']['_v'];
					$product_info['clean_name'] = $v['_c']['name']['_v'];

					$parsed_name = $this->parseName($v['_c']['name']['_v']);
					if($parsed_name)
					{
						$product_info['name'] = $parsed_name['name'];
						$product_info['dosage_form'] = $parsed_name['dosage_form'];
						$product_info['dosage_form_size'] = $parsed_name['dosage_form_size'];
						$product_info['size_of_a_unit'] = $parsed_name['size_of_a_unit'];
					}
					$product_info['price'] = $v['_c']['price']['_v'];
					$product_info['quantity'] = $v['_c']['quantity']['_v'];
					$product_info['is_vital'] = $v['_c']['is_vital']['_v'];
					$product_info['vat'] = $v['_c']['vat']['_v'];

					$collection->add($product_info);
				}

				return $collection;
			}
			else
			{
				throw new Exception('Не удалось получить прайс-лист');
			}
		}

		/**
		 * @param $orders_data
		 *
		 * @return bool|array
		 */
		public function createOrder($orders_data)
		{
			$builder = new PiluliOrderXmlBuilder();
			$xml = $builder->build($orders_data);

			$this->request_sender->setUrl($this->buildUrl() . '/exchange');
			$this->request_sender->setParams(array(
												  'xml' => $xml
											 ));
			if(!$this->request_sender->sendPostRequest())
			{
				return array(
					'status' => 1
				);
			}

			$response = $this->request_sender->getResponse();

			$data = $this->parseCreateOrderResponse($response);

			return $data;
		}

		/**
		 * @param $xml_data
		 *
		 * @return array
		 */
		private function parseCreateOrderResponse($xml_data)
		{
			$result = array(
				'status' => 0,
			);

			$data = $this->getXmlConverter()->convertToArray($xml_data);

			$result['xml_status'] = array();
			$result['xml_status']['status'] = $data['data']['_c']['error']['_c']['error_code']['_v'];
			$result['xml_status']['message'] = $data['data']['_c']['error']['_c']['error_description']['_v'];

			$result['orders_status'] = array();

			foreach($data['data']['_c']['orders']['_c']['order'] as $order_status_info)
			{
				if(isset($order_status_info['_c']))
				{
					$order_status_info = $order_status_info['_c'];
				}

				$status = $order_status_info['status']['_c']['status_code']['_v'];
				$info = array(
					'order_code' => $order_status_info['order_code']['_v'],
					'status' => $status,
					'message' => $order_status_info['status']['_c']['status_description']['_v'],
				);
				if(!$status)
				{
					$info['errors'] = null;
				}
				else
				{
					$info['errors'] = array();
					foreach($order_status_info['order_errors']['_c']['order_error'] as $error)
					{
						$c = array(
							'code' => $error['_c']['order_error_code']['_v'],
							'message' => $error['_c']['order_error_description']['_v'],
						);
						$info['errors'][] = $c;
					}
				}

				$result['orders_status'][] = $info;
			}

			return $result;
		}

		/**
		 * @return array
		 */
		public function getOrdersStatuses()
		{
			$url = $this->buildUrl() . '/exchange';
			$this->request_sender->setUrl($url);
			if(!$this->request_sender->sendGetRequest())
			{
				return array(
					'status' => 1
				);
			}
			else
			{
				$data = $this->request_sender->getResponse();

				return $this->parseGetOrdersStatusesResponse($data);
			}
		}

		private function parseGetOrdersStatusesResponse($xml_data)
		{
			$data = $this->getXmlConverter()->convertToArray($xml_data);

			$result = array(
				'status' => 0,
				'result' => array()
			);


			$orders = array();

			if(isset($data['orders']['_c']['order']))
			{
				foreach($data['orders']['_c']['order'] as $v)
				{
					if(isset($v['_c']))
					{
						$v = $v['_c'];
					}
					$order = array();
					$order['order_code'] = $v['order_code']['_v'];
					$order['name'] = $v['name']['_v'];
					$order['email'] = $v['email']['_v'];
					$order['address'] = $v['address']['_v'];
					$order['phone'] = $v['phone']['_v'];
					$order['status'] = $v['status']['_v'];
					$order['shipping_id'] = $v['shipping_id']['_v'];
					$order['shipping_cost'] = $v['shipping_cost']['_v'];
					$order['shipping_text'] = $v['shipping_text']['_v'];
					$order['payment_id'] = $v['payment_id']['_v'];
					$order['payment_text'] = $v['payment_text']['_v'];
					$order['comment'] = $v['comment']['_v'];
					$order['order_time'] = strtotime($v['order_time']['_v']);
					$order['total_cost'] = $v['total_cost']['_v'];
					$order['discount'] = $v['discount']['_v'];
					$order['cancellation_reason'] = isset($v['cancellation_reason']['_v']) ? $v['cancellation_reason']['_v'] : '';
					$order['products'] = array();

					foreach($v['products']['_c']['product'] as $product_info)
					{
						if(isset($product_info['_c']))
						{
							$product_info = $product_info['_c'];
						}
						$product = array(
							'code' => $product_info['code']['_v'],
							'name' => $product_info['name']['_v'],
							'amount' => $product_info['amount']['_v'],
							'price' => $product_info['price']['_v'],
						);

						$order['products'][] = $product;

					}

					$orders[] = $order;

				}
			}

			$result['result'] = $orders;


			return $result;
		}


		/**
		 * @param string $order_code
		 *
		 * @return int
		 */
		public function getOrderStatus($order_code)
		{
			$url = $this->buildUrl().'/exchange/'.$order_code;
			$this->request_sender->setUrl($url);

			if(!$this->request_sender->sendGetRequest())
			{
				return array(
					'status' => 1,
				);
			} else {
				$xml_data = $this->request_sender->getResponse();
				return $this->parseGetOrdersStatusesResponse($xml_data);
			}
		}

		/**
		 * @param $order_code
		 *
		 * @return bool
		 * @throws Exception
		 */
		public function cancelOrder($order_code)
		{
			throw new Exception('Not implemented');
		}

		private function buildUrl()
		{
			return 'http://' . $this->login . ':' . $this->password . '@smacs.ru';
		}

		private function convertToArray($xml_data)
		{
			return $this->getXmlConverter()->convertToArray($xml_data);
		}

		private function convertPriceXML($xml_data)
		{
			$xml_reader = new XMLReader();
			$xml_reader->XML($xml_data);
			$doc = new DOMDocument();

			$result = array();
			$result['products'] = array();
			$result['products']['_c'] = array();
			$result['products']['_c']['product'] = array();

			while($xml_reader->read() && $xml_reader->name != 'product')
				;
			while($xml_reader->name === 'product')
			{
				/**
				 * @var SimpleXMLElement $node
				 */
				$node = simplexml_import_dom($doc->importNode($xml_reader->expand(), true));
				$item = array(
					'_c' => array(
						'name' => array(
							'_v' => trim((string)$node->name),
						),
						'manufacturer' => array(
							'_v' => trim((string)$node->manufacturer),
						),
						'code' => array(
							'_v' => trim((string)$node->code),
						),
						'price' => array(
							'_v' => trim((string)$node->price),
						),
						'quantity' => array(
							'_v' => trim((string)$node->quantity),
						),
						'is_vital' => array(
							'_v' => trim((string)$node->is_vital),
						),
						'vat' => array(
							'_v' => trim((string)$node->vat),
						),
					)
				);

				$result['products']['_c']['product'][] = $item;

				unset($node);
				$xml_reader->next('product');
			}

			return $result;
		}

		private function parseName($name)
		{
			return $this->name_parser->parse($name);
		}
	}

