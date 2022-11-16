<?php
	abstract class ModerateModelManager extends ModelManager
	{
		protected $moderated_entity_name = NULL;

		abstract public function createNewRevision($revision_condition);

		abstract public function getLastRevisionNumberByEntityId($entity_id);

		abstract public function publishRevision($entity_id);

		abstract public function getCurrentRevision($revision_condition);

		protected  function beforeSave(DynamicModel $model)
		{
			if ($model->moderate_status_id == ModerateStatusModel::PUBLISHED)
			{
				$model->publishRevision();
			}
		}

		/**
		 * @param $entity_id
		 *
		 * @return ModerateModel
		 */
		public function getModeratedEntityByModel(ModerateModel $model)
		{
			$entity_id = $model->{$this->moderated_entity_name.'_id'};
			return $this->getModeratedEntityById($entity_id);
		}

		public function getModerateEntityName()
		{
			return $this->moderated_entity_name;
		}

		public function getModeratedEntityById($entity_id)
		{
			if (is_array($entity_id))
			{
				$entity_id = $entity_id[$this->moderated_entity_name.'_id'];
			}


			$manager = ModelManagerFactory::getByName($this->moderated_entity_name);
			$model = $manager->getOneById($entity_id);

			return $model;
		}

	}