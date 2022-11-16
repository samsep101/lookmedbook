<?php
	abstract class ModelIndexCommand implements ISearchIndexCommand
	{
		/**
		 * @var ISearchIndexModelManager
		 */
		protected $model_manager;

		/**
		 * @var IElasticSearchModelIndexControl
		 */
		protected $index_manager;


		/**
		 * @return ISearchIndexModelManager
		 */
		protected function getModelManager()
		{
			return $this->model_manager;
		}

		protected function getIndexManager()
		{
			return $this->index_manager;
		}

		/**
		 * Переиндексировать все записи
		 *
		 * @return mixed
		 */
		public function reIndexAll()
		{
			$this->getModelManager()->setReIndexStatus();
			$this->processNotIndexedDocuments();
		}

		/**
		 * Обработать документы, которые должны быть проиндексированы
		 *
		 * @return mixed
		 */
		public function processNotIndexedDocuments()
		{
			while($data = $this->getModelManager()->getListForAddToIndex(500))
			{
				$this->getIndexManager()->addDocuments($data);
				$this->getModelManager()->markAsIndexed($data);
			}
		}

		/**
		 * Обработка документов, которые должны быть удалены
		 *
		 * @return mixed
		 */
		public function processDeletedDocuments()
		{
			ModelManager::disableEntityMapGlobal();

			$ids = $this->getIndexManager()->getDocumentsIds();

			$delete_ids = array();
			foreach($ids as $id)
			{
				if(!$this->getModelManager()->getOneById($id))
				{
					$delete_ids[] = $id;
				}
			}

			if(count($ids))
			{
				$this->getIndexManager()->deleteByIds($delete_ids);
			}

		}
	}