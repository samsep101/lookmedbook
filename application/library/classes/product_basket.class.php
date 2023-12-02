<?php
	class ProductBasket
	{
		private $products = array();

		public function __construct()
		{
			$this->products = isset($_SESSION['__emagazine']) ? $_SESSION['__emagazine'] : array();
		}


		public function getProductList()
		{
			$result = array();

			/**
			 * @var ProductManager $product_manager
             * @var ProductModel $product_model
			 */

			$product_manager = ModelManagerFactory::getByName('product');
			if($this->products)
			{
				foreach($this->products as $product)
				{
					if(($product['count'] > 0) && $product_model = $product_manager->getOneById($product['id']))
					{
                        $product['manufacturer'] = $product_model->manufacturer->name;
						$result[$product['id']] = $product;
					}
				}
			}
			return $result;
		}

		public function getTotalCount()
		{
			$count = 0;

			foreach($this->products as $product)
			{
				$count += $product['count'];
			}

			return $count;
		}

		public function getTotalPrice()
		{
			$result = 0;

			foreach($this->products as $product)
			{
				$result += ($product['price']*$product['count']);
			}

			return $result;
		}

		public function deleteProduct($product_id)
		{
			unset($this->products[$product_id]);
			return true;
		}

		public function clearBasket()
		{
			$this->products = array();
			$this->saveBasket();
		}

		public function setProductCount($product_id, $count)
		{
			if(!isset($this->products[$product_id]))
			{
				/**
				 * @var ProductManager $product_manager
				 * @var ProductModel $product
				 */
				$product_manager = ModelManagerFactory::getByName('product');
				$product =  $product_manager->getOneById($product_id);

				if(!$product)
				{
					return false;
				}

				$this->products[$product_id] = array(
					'id' => $product->getId(),
					'name' => $product->clean_name,
					'price' => $product->price,
				);
			}

			$this->products[$product_id]['count'] = $count;

			$this->saveBasket();
			return true;
		}

		public function getProductInfoByProductId($product_id)
		{
			return isset($this->products[$product_id]) ? $this->products[$product_id] : null;
		}



		private function saveBasket()
		{
			$_SESSION['__emagazine'] = $this->products;
		}
	}
