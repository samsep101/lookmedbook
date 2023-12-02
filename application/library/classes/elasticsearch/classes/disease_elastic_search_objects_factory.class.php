<?php
	class DiseaseElasticSearchObjectsFactory extends ElasticSearchObjectFactory
	{
		/**
		 * @return IElasticSearchMapping
		 */
		public function getMapper()
		{
			return new DiseaseElasticSearchMapping();
		}

		/**
		 * @return IElasticSearchFormatter
		 */
		public function getFormatter()
		{
			return new DiseaseElasticSearchFormatter();
		}
	}