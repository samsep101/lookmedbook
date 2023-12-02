<?php

	/**
	 * Class ElasticSearchObjectFactory
	 * Фабрика для объектов, которые используются для работы с ElasticSearch для конкретной модели
	 *
	 * Реализует паттерн "Абстрактная фабрика"
	 */
	abstract class ElasticSearchObjectFactory implements IElasticSearchObjectFactory
	{
		/**
		 * @return IElasticSearchMapping
		 */
		abstract public function getMapper();

		/**
		 * @return IElasticSearchFormatter
		 */
		abstract public function getFormatter();
	}