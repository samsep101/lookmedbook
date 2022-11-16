<?php
	class ProductSupplierServiceFactory
	{
		/**
		 * @return PiluliApi
		 */
		public static function getService()
		{
			return new PiluliApi('lookmedbook', '7Kjf238N');
			//return new PiluliApi(SettingsManager::get('piluli_login'), SettingsManager::get('piluli_password'));
		}
	}