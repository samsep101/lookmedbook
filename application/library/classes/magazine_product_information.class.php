<?php
	class MagazineProductInformation implements IProductInformationService
	{
		/**
		 * @var IProductSupplierService
		 */
		private $piluli_api;

		public function __construct()
		{
			$this->piluli_api = ProductSupplierServiceFactory::getService();
		}

		/**
		 * @return SupplierProductInformation[]
		 */
		public function getProductsList()
		{
			/**
			 * @var ObjectCollection $data
			 */
			$data = $this->piluli_api->getPriceList();
			//$data->setDataMapper(new ProductInformationMapper());
			return $data;
		}
	}