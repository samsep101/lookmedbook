<?php

	interface IElasticSearchObjectFactory
	{
		/**
		 * @return IElasticSearchMapping
		 */
		public function getMapper();

		/**
		 * @return IElasticSearchFormatter
		 */
		public function getFormatter();
	}