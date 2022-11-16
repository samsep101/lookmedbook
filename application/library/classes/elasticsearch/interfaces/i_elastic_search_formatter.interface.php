<?php

	/**
	 * Преобразование моделей к виду, в котором данные хранятся в ElasticSearch
	 *
	 * Interface IElasticSearchFormatter
	 */
	interface IElasticSearchFormatter
	{
		/**
		 * @param DynamicModel $model
		 *
		 * @return \Elastica\Document
		 */
		public function toElasticSearchView(DynamicModel $model);
	}
