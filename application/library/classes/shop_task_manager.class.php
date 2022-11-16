<?php
	class ShopTaskManager
	{
		public static function updateProductCategoriesActiveStatus()
		{
			/**
			 * @var ProductCategoryManager $product_category_manager
			 */
			$product_category_manager = ModelManagerFactory::getByName('product_category');
			$product_category_manager->updateActiveStatus();
		}

		public static function downloadProductsImage()
		{
			/**
			 * @var ProductManager $product_manager
			 * @var ProductModel $product
			 * @var BingKeysManager $bing_keys
			 */

			ini_set('memory_limit', '512M');

			if(!MemcacheAdapter::get('download_images_bing'))
			{
				MemcacheAdapter::set('download_images_bing', 1);

				$product_manager = ModelManagerFactory::getByName('product');

				$product_search_criteria = new ProductSearchCriteria();
				$product_search_criteria->page = 1;
				$product_search_criteria->by_page = 100;
				$product_search_criteria->image_find_status_id = ImageFindStatusModel::IN_QUEUE;

				$products = $product_manager->getListByModelSearchCriteria($product_search_criteria);

				foreach($products as $product)
				{
					self::downloadProductImage($product->getId());
					sleep(1);
				}

				MemcacheAdapter::remove('download_images_bing');
			}
		}

		public static function downloadProductImage($product_id)
		{
			/**
			 * @var ProductManager $product_manager
			 * @var ProductModel $product
			 */

			$product_manager = ModelManagerFactory::getByName('product');
			$product = $product_manager->getOneById($product_id);

			$api = ImageSearchApiFactory::getInstance();

			$search_params = array(
				'title' => $product->clean_name,
				'size' => 'Large',
				'count' => 10
			);
			$images = $api->searchImage($search_params);

			$api->incrementTransactionsCount();

			if($images)
			{
                $alias = ($product->image_alias) ? $product->image_alias :  null;

				$image_id = ImageUploader::loadImage($images[0], 'product/', $alias);

				$count = 0;
				while(!$image_id && ($count < 3))
				{
					$image_id = ImageUploader::loadImage($images[0], 'product/', $alias);
					$count++;
				}

				if(!$image_id && isset($images[1]))
				{
					$image_id = ImageUploader::loadImage($images[1], 'product/', $alias);
				}

				if($image_id)
				{
					$product->image_id = $image_id;
					$product->image_find_status_id = ImageFindStatusModel::AUTO;
				} else {
					$product->image_find_status_id = ImageFindStatusModel::NOT_FOUND;
				}
				$product->save();
			}
			else
			{
				$product->image_find_status_id = ImageFindStatusModel::NOT_FOUND;
				$product->save();
			}
		}

		public static function updateProductsInformation()
		{
			ini_set('memory_limit', '512M');

			/**
			 * @var ProductManager $product_manager
			 */
			$product_manager = ModelManagerFactory::getByName('product');

			foreach($product_manager->getIterator() as $product)
			{
				/**
				 * @var ProductModel $product
				 */
				self::updateProductInformation($product->getId());
			}
		}

		public static function updateProductInformation($product_id)
		{
			/**
			 * @var ProductManager $product_manager
			 * @var ProductModel $product
			 */
			$product_manager = ModelManagerFactory::getByName('product');

			$product = $product_manager->getOneById($product_id);

			if($product->fill_information_status_id != FillInformationStatusModel::IN_QUEUE)
			{
				return;
			}

			$vidal_finder = new VidalFinder();
			$vidal_information_fill = new VidalInformationFill();

			if(!$product->ru_name)
			{
				$product->fill_information_status_id = FillInformationStatusModel::NOT_FOUND;
				$product->save();

				return;
			}
			/**
			 * @var ProductModel $product
			 */
			$dosage_form = $product->product_dosage_form_id ? $product->product_dosage_form->name : '';
			$vidal_product = $vidal_finder->findByNameAndDosageFormName($product->ru_name, $dosage_form);

			if($vidal_product)
			{
				$vidal_information_fill->fill($product, $vidal_product);
			}

			$product->fill_information_status_id = $vidal_finder->getFindStatusId();
			$product->save();
		}


		public static function updateProductList()
		{
			set_time_limit(0);
			ini_set('memory_limit', '512M');
			$service = ProductInformationServiceFactory::getService();

			$products_information = $service->getProductsList();

			/**
			 * @var ProductManager $product_manager
			 * @var ManufacturerManager $manufacturer_manager
			 * @var PiluliProductToProductCategoryManager $piluli_category_lookup_manager
			 * @var ProductDosageFormManager $product_dosage_form_manager
			 * @var VidalDocumentManager $vidal_document_manager
			 */
			$product_manager = ModelManagerFactory::getByName('product');
			$manufacturer_manager = ModelManagerFactory::getByName('manufacturer');
			$piluli_category_lookup_manager = ModelManagerFactory::getByName('piluli_product_to_product_category');
			$product_dosage_form_manager = ModelManagerFactory::getByName('product_dosage_form');

			if($products_information)
			{
				foreach($products_information as $product_information)
				{
					$product = $product_manager->getOneByServiceCode($product_information->code);

					if(!$product)
					{
						$product = new ProductModel();
						$lookup = $piluli_category_lookup_manager->getOneByServiceProductCode($product_information->code);
						if(!$lookup || !$lookup->product_category || !$lookup->product_category->is_used)
						{
							continue;
						}
						$product->product_category_id = $lookup->product_category_id;

						$manufacturer = $manufacturer_manager->getOneByName($product_information->manufacturer);
						if(!$manufacturer)
						{
							$manufacturer = new ManufacturerModel();
							$manufacturer->name = $product_information->manufacturer;
							$manufacturer->save();
						}

						$product->service_code = $product_information->code;
						$product->ru_name = $product_information->name;
						$product->size_of_a_unit = $product_information->size_of_a_unit;
						$product->clean_name = $product_information->clean_name;

						$dosage_form = null;
						if($product_information->dosage_form)
						{
							$dosage_form = $product_dosage_form_manager->getOneByName($product_information->dosage_form);
							if(!$dosage_form)
							{
								$dosage_form = new ProductDosageFormModel();
								$dosage_form->name = $product_information->dosage_form;
								$dosage_form->save();
							}
						}

						if($product_information->dosage_form_size)
						{
							$product->dosage_form_size = $product_information->dosage_form_size;
						}

						if($dosage_form)
						{
							$product->product_dosage_form_id = $dosage_form->getId();
						}

						$product->manufacturer_id = $manufacturer->getId();
						$product->save();

						self::updateProductInformation($product->getId());
					}
				}
			}
		}

		public static function updateAvailabilityOfProducts()
		{
			set_time_limit(0);
			ini_set('memory_limit', '1024M');
			$service = ProductSupplierServiceFactory::getService();

			$products_information = $service->getPriceList();

			/**
			 * @var ProductManager $product_manager
			 */
			$product_manager = ModelManagerFactory::getByName('product');

			/**
			 * @var $product_information_manager
			 */
			$product_information_manager = ModelManagerFactory::getByName('product_information');


			$dt_start = date('Y-m-d H:i:s');
			foreach($products_information as $product_information)
			{
				$product = $product_manager->getOneByServiceCode($product_information->code);

				if(!$product)
				{
					continue;
				}

				$product->price = $product_information->price;
				$product->is_vital = $product_information->is_vital;
				$product->vat = $product_information->vat;
				$product->quantity = $product_information->quantity;
				$product->dt_actual = date('Y-m-d H:i:s');
				$product->save();

				$product_information_manager->clearRegister();
				$product_manager->clearRegister();
			}

            /* Убрана актуализация данных в связи с задачей, целью которой
            необходило было преобразовать каталог в список товаров носящую
            только справочную информацю */

//			$product_manager->updateActiveStatusByDtActual($dt_start);
			ShopTaskManager::updateProductCategoriesActiveStatus();
		}

		public static function createOrders()
		{
			/**
			 * @var OrderManager $order_manager
			 */
			$order_manager = ModelManagerFactory::getByName('order');

			$orders = $order_manager->getListByOrderStatusId(OrderStatusModel::LMB_IN_QUEUE);

			if(!count($orders))
			{
				return;
			}

			$orders_data = PiluliOrderDataFormatHelper::format($orders);
			$service = ProductSupplierServiceFactory::getService();

			$response = $service->createOrder($orders_data);

			if($response['status'] !== 0)
			{
				return;
			}
			else
			{
				$failure_flag = false;
				if($response['xml_status']['status'] != 0)
				{
					$failure_flag = true;
				}
				else
				{
					foreach($response['orders_status'] as $v)
					{
						$order = $order_manager->getOneBySystemCode($v['order_code']);
						if($v['status'] != 0)
						{
							$failure_flag = true;
							$order->order_status_id = OrderStatusModel::NEED_CHECK;
							$order->save();
						}
						else
						{
							$order->order_status_id = OrderStatusModel::IN_QUEUE;
							$order->save();
						}
					}
				}

				if($failure_flag)
				{
					$piluli_request_error = new PiluliRequestErrorModel();
					$piluli_request_error->request_data = json_encode($orders_data, JSON_UNESCAPED_UNICODE);
					$piluli_request_error->response = json_encode($response, JSON_UNESCAPED_UNICODE);
					$piluli_request_error->save();
				}
			}
		}

		public static function updateOrdersStatuses()
		{
			$service_api = ProductSupplierServiceFactory::getService();
			$data = $service_api->getOrdersStatuses();

						if(($data['status'] != 0) || !count($data['result']))
			{
				return;
			}

			self::processPiluliOrderData($data);
		}

		public static function updateNotProcessedOrders()
		{
			/**
			 * @var OrderManager $order_manager
			 */
			$order_manager = ModelManagerFactory::getByName('order');

			$orders = $order_manager->getNotProcessedOrdersByMinutesCount(20);

			$api = ProductSupplierServiceFactory::getService();

			foreach($orders as $order)
			{
				$data = $api->getOrderStatus($order->system_code);
				if(($data['status'] != 0) || (count($data['result']) == 0))
				{
					continue;
				}

				self::processPiluliOrderData($data);
			}
		}

		private static function processPiluliOrderData($data)
		{
			/**
			 * @var OrderManager $order_manager
			 */
			$order_manager = ModelManagerFactory::getByName('order');

			foreach($data['result'] as $order_info)
			{
				$order = $order_manager->getOneBySystemCode(trim($order_info['order_code']));
				if($order)
				{
					$new_status = (int)$order_info['status'];
					if($new_status == 0)
					{
						$new_status = OrderStatusModel::IN_QUEUE;
					}

					$order->name = $order_info['name'];
					$order->shipping_cost = $order_info['shipping_cost'];
					$order->total_cost = $order_info['total_cost'];
					$order->shipping_type_id = $order_info['shipping_id'];

					if($new_status != $order->order_status_id)
					{
						$order->order_status_id = $new_status;
						$order->save();
					}

				}
			}
		}
	}