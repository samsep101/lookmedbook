<?php

	class ClinicElasticSearchObjectsFactory extends ElasticSearchObjectFactory
	{
		/**
		 * @return IElasticSearchMapping
		 */
		public function getMapper()
		{
			return new ClinicElasticSearchMapping();
		}

		/**
		 * @return IElasticSearchFormatter
		 */
		public function getFormatter()
		{
			return new ClinicElasticSearchFormatter();
		}

	}