<?php

	/**
	 * Class DoctorElasticSearchObjectFactory
	 *
	 */
	class DoctorElasticSearchObjectFactory extends ElasticSearchObjectFactory
	{
		/**
		 * @return IElasticSearchMapping
		 */
		public function getMapper()
		{
			return new DoctorElasticSearchMapping();
		}

		/**
		 * @return IElasticSearchFormatter
		 */
		public function getFormatter()
		{
			return new DoctorElasticSearchFormatter();
		}
	}