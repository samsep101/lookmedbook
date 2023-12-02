<?php
	class LaboratoryElasticSearchObjectsFactory implements IElasticSearchObjectFactory
	{
		/**
		 * @return IElasticSearchMapping
		 */
		public function getMapper()
		{
			return new LaboratoryElasticSearchMapping();
		}

		/**
		 * @return IElasticSearchFormatter
		 */
		public function getFormatter()
		{
			return new LaboratoryElasticSearchFormatter();
		}

	}