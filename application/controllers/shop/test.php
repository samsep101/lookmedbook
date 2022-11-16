<?php
	class TestShopController extends BaseController
	{
		public function fillCategory()
		{
			set_time_limit(0);
			$xml = file_get_contents('./data/category.xml');

			$xml_parser = new XmlConverter();
			$data = $xml_parser->toArray($xml);

			/**
			 * @var ProductCategoryManager $product_category_manager
			 */
			$product_category_manager = ModelManagerFactory::getByName('product_category');
			foreach($data['categories']['_c']['category'] as $v)
			{
				$product_category = $product_category_manager->getOneByServiceId($v['_a']['id']);
				if(!$product_category)
				{
					$product_category = new ProductCategoryModel();
					$product_category->name = $v['_v'];
					$product_category->service_id = $v['_a']['id'];
					$product_category->save();
				}
			}
		}

		public function fillProductToCategory()
		{
			set_time_limit(0);
			ini_set('memory_limit', '2048M');
			$xml = file_get_contents('./data/products_to_categories.xml');

			$xml_parser = new XmlConverter();
			$data = $xml_parser->toArray($xml);

			/**
			 * @var ProductCategoryManager $product_category_manager
			 */
			$product_category_manager = ModelManagerFactory::getByName('product_category');

			foreach($data['products_to_categories']['_c']['pTc'] as $v)
			{
				$product_category = $product_category_manager->getOneByServiceId($v['_c']['c_id']['_v']);

				if(!$product_category)
				{
					continue;
				}

				$product_to_product_category = new PiluliProductToProductCategoryModel();
				$product_to_product_category->service_product_code = $v['_c']['p_model']['_v'];
				$product_to_product_category->product_category_id = $product_category->getId();
				$product_to_product_category->save();
			}

			/**
			 * @var ProductCategoryManager $product_category_manager
			 */
			/*
			$product_category_manager = ModelManagerFactory::getByName('product_category');
			foreach($data['categories']['_c']['category'] as $v)
			{
				$product_category = $product_category_manager->getOneByServiceId($v['_a']['id']);
				if(!$product_category)
				{
					$product_category = new ProductCategoryModel();
					$product_category->name = $v['_v'];
					$product_category->service_id =  $v['_a']['id'];
					$product_category->save();
				}
			}
			*/
		}

		public function fillParentId()
		{
			set_time_limit(0);
			$xml = file_get_contents('./data/category.xml');

			$xml_parser = new XmlConverter();
			$data = $xml_parser->toArray($xml);

			/**
			 * @var ProductCategoryManager $product_category_manager
			 */
			$product_category_manager = ModelManagerFactory::getByName('product_category');
			foreach($data['categories']['_c']['category'] as $v)
			{
				if(!isset($v['_a']['parentId']))
				{
					continue;
				}
				/**
				 * @var ProductCategoryModel $product_category
				 */
				$product_category = $product_category_manager->getOneByServiceId($v['_a']['id']);
				$product_category->parent_id = $product_category_manager->getOneByServiceId($v['_a']['parentId'])->getId();
				$product_category->save();
			}
		}

		public function fillBaseId()
		{
			/**
			 * @var ProductCategoryManager $product_category_manager
			 */
			$product_category_manager = ModelManagerFactory::getByName('product_category');

			foreach($product_category_manager->getIterator() as $product_category)
			{
				/**
				 * @var ProductCategoryModel $product_category
				 */
				$parent = $product_category->parent;

				while($parent && $parent->parent)
				{
					$parent = $parent->parent;
				}

				if($parent)
				{
					$product_category->base_id = $parent->getId();
					$product_category->save();
				}
			}

			exit();
		}

		public function testPiluliProductNameParser()
		{
			$name_parser = new PiluliProductNameParser();
			$product_manager = new ProductManager();

			$count = 0;
			$i = 0;
			foreach($product_manager->getIterator() as $product)
			{
				/**
				 * @var ProductModel $product
				 */

				if(!$name_parser->parse($product->ru_name))
				{
					echo 'ERROR - ' . $product->ru_name . '<br />';
					$count++;
				}
				else
				{
					//echo 'OK '.$product->ru_name.'<br />';
				}
				$i++;

				if($i == 10)
				{
					break;
				}
			}

			echo $count;
		}

		public function testVidalIntegration()
		{
			/**
			 * @var ProductManager $product_manager
			 * @var VidalDocumentManager $vidal_document_manager
			 */
			ini_set('memory_limit', '512M');
			$product_manager = ModelManagerFactory::getByName('product');
			$vidal_finder = new VidalFinder();

			$vidal_information_fill = new VidalInformationFill();
			$i = 0;

			/**
			 * @var ShopContentManagerLogManager $log_manager
			 */
			$log_manager = ModelManagerFactory::getByName('shop_contentmanager_log');

			foreach($product_manager->getIterator() as $product)
			{
				/**
				 * @var ProductModel $product
				 */
				if(!$log_manager->checkIsStatusChangedByProductId($product->getId()))
				{
					continue;
				}

				if(!$product->ru_name)
				{
					$product->fill_information_status_id = FillInformationStatusModel::NOT_FOUND;
					$product->save();
					continue;
				}

				/**
				 * @var ProductModel $product
				 */
				$dosage_form = $product->product_dosage_form_id ? $product->product_dosage_form->name : '';
				$vidal_product = $vidal_finder->findByNameAndDosageFormNameAndDosageFormSize($product->ru_name, $dosage_form, $product->dosage_form_size);

				echo $product->clean_name . ' - ' . $vidal_finder->getFindStatusId() . "<br />";
				if($vidal_product)
				{
					$vidal_information_fill->fill($product, $vidal_product);
				}

				$product->fill_information_status_id = $vidal_finder->getFindStatusId();
				$product->save();

				$i++;

			}
		}

		public function fillProductDosageFormSize()
		{
			ini_set('memory_limit', '256M');
			/**
			 * @var ProductManager $product_manager
			 */
			$product_manager = ModelManagerFactory::getByName('product');

			$piluli_parser = new PiluliProductNameParser();

			foreach($product_manager->getIterator() as $product)
			{
				/**
				 * @var ProductModel $product
				 */
				$data = $piluli_parser->parse($product->clean_name);
				$product->size_of_a_unit = $data['size_of_a_unit'];
				$product->dosage_form_size = $data['dosage_form_size'];
				$product->save();
			}
		}

		public function generateAliases()
		{
			/**
			 * @var ProductCategoryManager $product_category_manager
			 */
			$product_category_manager = ModelManagerFactory::getByName('product_category');

			foreach($product_category_manager->getIterator() as $product_category)
			{
				/**
				 * @var ProductCategoryModel $product_category
				 */
				$product_category->save();
			}

			/**
			 * @var ProductManager $product_manager
			 */
			$product_manager = ModelManagerFactory::getByName('product');

			foreach($product_manager->getIterator() as $product)
			{
				/**
				 * @var ProductModel $product
				 */
				$product->save();
			}
			exit();
		}

		public function xmlConverter()
		{
			$xml_reader = new XMLReader();

			$xml_reader->open('./data/products_to_categories.xml');

			$xml_reader->read();
			$result = array();

			$result[$xml_reader->name] = array();


			if($xml_reader->hasAttributes)
			{

			}
			$xml_reader->read();
		}

		public function beforeAction()
		{
			set_time_limit(0);
			parent::beforeAction();
		}

		public function beforeRender()
		{
			exit();
		}

		public function fillVidalDosageForm()
		{
			/**
			 * @var VidalDocumentManager $vidal_document_manager
			 */
			$vidal_document_manager = ModelManagerFactory::getByName('vidal_product');

			foreach($vidal_document_manager->getIterator() as $vidal_document)
			{
				/**
				 * @var VidalProductModel $vidal_document
				 */
				$vidal_document->rus_name_clean = trim(preg_replace('/<[^>]+>.+<\/.+?>/imsu', '', $vidal_document->RusName));

				$preg = '/<B>(.+?)<\/B>/ims';
				if(preg_match($preg, $vidal_document->Composition, $matches))
				{
					$vidal_document->dosage_form = strip_tags(mb_strtolower($matches[1], 'utf-8'));
					$vidal_document->dosage_form = str_replace('&loz;', '', $vidal_document->dosage_form);
					$vidal_document->dosage_form = trim($vidal_document->dosage_form);
				}

				$unit_size = '';
				if(preg_match('/<TD Align=Center>([^<]+)<\/TD><\/TR><\/TABLE>/ims', $vidal_document->Composition, $matches))
				{
					if(preg_match('/[0-9]/', $matches[1]))
					{
						$unit_size = $matches[1];
					}
				}

				$vidal_document->dosage_form_size = $unit_size;
				$vidal_document->save();
			}
		}

		public function clearBasket()
		{
			$this->product_basket->clearBasket();
		}

		public function setCategoryActiveStatus()
		{
			ShopTaskManager::updateProductCategoriesActiveStatus();
		}

		public function testBuildOrderXml()
		{
			/**
			 * @var OrderManager $order_manager
			 */
			$order_manager = new OrderManager();
			$orders = $order_manager->getListByOrderStatusId(OrderStatusModel::LMB_IN_QUEUE);

			$piluli_api = ProductSupplierServiceFactory::getService();
			$builder = new PiluliOrderXmlBuilder();

			$data = PiluliOrderDataFormatHelper::format($orders);
			Test::dump($piluli_api->createOrder($data));
			exit();
		}

		public function fillDosageFormLookup()
		{

			/**
			 * @var VidalDosageFormManager $vidal_dosage_form_manager
			 * @var ProductDosageFormManager $dosage_form_manager
			 * @var PiluliDosageFormLookupManager $piluli_dosage_form_lookup_manager
			 */
			$vidal_dosage_form_manager = ModelManagerFactory::getByName('vidal_dosage_form');
			$dosage_form_manager = ModelManagerFactory::getByName('product_dosage_form');
			$piluli_dosage_form_lookup_manager = ModelManagerFactory::getByName('piluli_dosage_form_lookup');

			foreach($dosage_form_manager->getIterator() as $dosage_form)
			{
				/**
				 * @var ProductDosageFormModel $dosage_form
				 */
				$vidal_dosage_forms = $vidal_dosage_form_manager->getListByNamePart($dosage_form->name);

				foreach($vidal_dosage_forms as $vidal_dosage_form)
				{
					if(!$piluli_dosage_form_lookup_manager->getOneByPiluliNameAndVidalName($dosage_form->name, $vidal_dosage_form->name))
					{
						$piluli_dosage_form_lookup = new PiluliDosageFormLookupModel();
						$piluli_dosage_form_lookup->piluli_name = $dosage_form->name;
						$piluli_dosage_form_lookup->vidal_name = $vidal_dosage_form->name;
						$piluli_dosage_form_lookup->save();
					}
				}
			}
		}

		public function getVidalDosageFormList()
		{

			/**
			 * @var VidalDosageFormManager $vidal_dosage_form_manager
			 * @var ProductDosageFormManager $dosage_form_manager
			 * @var PiluliDosageFormLookupManager $piluli_dosage_form_lookup_manager
			 */
			$vidal_dosage_form_manager = ModelManagerFactory::getByName('vidal_dosage_form');
			$dosage_form_manager = ModelManagerFactory::getByName('product_dosage_form');
			$piluli_dosage_form_lookup_manager = ModelManagerFactory::getByName('piluli_dosage_form_lookup');

			foreach($vidal_dosage_form_manager->getIterator() as $vidal_dosage_form)
			{
				if(!$piluli_dosage_form_lookup_manager->getListByVidalName($vidal_dosage_form->name))
				{
					echo $vidal_dosage_form->name.'<br/>';
				}
			}
		}

		public function testPiluli()
		{
			$pilili_api = ProductSupplierServiceFactory::getService();
			Test::dump($pilili_api->getOrdersStatuses());
		}

		public function fillSpecialInformationAndPharmaDelivery()
		{
			/**
			 * @var ProductManager $product_manager
			 * @var VidalDocumentManager $vidal_document_manager
			 */
			ini_set('memory_limit', '512M');
			$product_manager = ModelManagerFactory::getByName('product');
			$vidal_finder = new VidalFinder();

			foreach($product_manager->getIterator() as $product)
			{

				if(!$product->ru_name)
				{
					$product->fill_information_status_id = FillInformationStatusModel::NOT_FOUND;
					$product->save();
					continue;
				}

				/**
				 * @var ProductModel $product
				 */
				$dosage_form = $product->product_dosage_form_id ? $product->product_dosage_form->name : '';
				$vidal_product = $vidal_finder->findByNameAndDosageFormNameAndDosageFormSize($product->ru_name, $dosage_form, $product->dosage_form_size);

				if($vidal_product->document)
				{
					$product->extend_information->pharm_delivery = $vidal_product->document->PharmDelivery;
					$product->extend_information->special_information = $vidal_product->document->SpecialInstruction;
				}

				$product->extend_information->save();

				$product->save();

			}
		}
	}
