<?php
	class ProductHelper
	{
		public static function getQuantityByProductId($product_id)
		{
			/**
			 * @var ProductManager $product_manager
			 */
			$product_manager = ModelManagerFactory::getByName('product');
			return $product_manager->getQuantityByProductId($product_id);
		}

		public static function getImageByProductId($product_id)
		{
			/**
			 * @var ProductManager $product_manager
			 * @var ProductModel $product
			 */
			$product_manager = ModelManagerFactory::getByName('product');
			$product = $product_manager->getOneById($product_id);
			return $product->image;
		}

		public static function getIsImageConfirmedByProductId($product_id)
		{
			/**
			 * @var ProductManager $product_manager
			 * @var ProductModel $product
			 */
			$product_manager = ModelManagerFactory::getByName('product');
			$product = $product_manager->getOneById($product_id);
			return $product->is_image_confirmed;
		}

	}