<?php
	class ObjectCollectionFactory
	{
		/**
		 * @param $name
		 *
		 * @return ObjectCollection
		 * @throws Exception
		 */
		public static function getCollection($name)
		{
			switch($name)
			{
				case 'supplier_product_information':
					$collection = new ObjectCollection();
					$collection->setDataMapper(new SupplierProductInformationMapper());
					return $collection;
				default:
					throw new Exception('Undefined method type');
			}
		}
	}