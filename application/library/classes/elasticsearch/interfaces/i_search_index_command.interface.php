<?php

	interface ISearchIndexCommand
	{
		/**
		 * Переиндексировать все записи
		 *
		 * @return mixed
		 */
		public function reIndexAll();

		/**
		 * Обработать документы, которые должны быть проиндексированы
		 *
		 * @return mixed
		 */
		public function processNotIndexedDocuments();

		/**
		 * Обработка документов, которые должны быть удалены
		 *
		 * @return mixed
		 */
		public function processDeletedDocuments();

	}