<?php
	class ProductInformationServiceFactory
	{
		public static function getService()
		{
			return new MagazineProductInformation();
		}
	}