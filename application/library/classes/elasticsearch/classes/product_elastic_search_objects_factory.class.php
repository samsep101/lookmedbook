<?php
	class ProductElasticSearchObjectsFactory
	{
		/**
		 * @return IElasticSearchMapping
		 */
		public function getMapper()
		{
			return new ProductElasticSearchMapping();
		}

		/**
		 * @return IElasticSearchFormatter
		 */
		public function getFormatter()
		{
			return new ProductElasticSearchFormatter();
		}
	}