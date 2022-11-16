<?php
class ListModerateModelManager extends ModerateModelManager
{
	protected $moderated_list_name;
	protected $entity_table;

	protected $revision_conditions = array();

	public function getEntityTable()
	{
		return $this->entity_table;
	}

	public function getRevisionConditions()
	{
		return $this->revision_conditions;
	}

	protected function checkRevisionCondition($checked_condition)
	{
		if ($this->revision_conditions)
		{
			if (!is_array($checked_condition))
				throw new Exception('Некорретный параметр для условия выборки');

			foreach($this->revision_conditions as $condition_name)
			{
				if (!isset($checked_condition[$condition_name]))
				{
					throw new Exception('Не хватает параметра для условия выборки');
				}
			}
		}
	}

	public function getModeratedEntityName()
	{
		return $this->moderated_entity_name;
	}

	public function createNewRevision($revision_condition)
	{
		$revision_condition = $this->processRevisionCondition($revision_condition);

		// todo: переделать этот блок
		// Если в качестве условия передан один идентификатор,
		// то проверяется наличие соответствующей записи в БД
		if (!is_array($revision_condition))
		{
			$entity = $this->getModeratedEntityById($revision_condition);
			if (!$entity)
				throw new Exception('Модерируется неизвестная сущность');
		}

		// Получение данных из основных таблиц
		// классы Manager для этих таблиц должны иметь соответствующие метода
		$data = $this->getModeratedDataByRevisionCondition($revision_condition);

		// Присваиваем новой ревизии новый идентификатор (на 1 больше предыдущего)
		$revision_number = $this->getLastRevisionNumberByRevisionCondition($revision_condition) + 1;



		$moderate_list_revision = new ModerateListRevisionModel();

        foreach($revision_condition as $field_name => $field_value)
        {
            $moderate_list_revision->{$field_name} = $field_value;
        }

		$moderate_list_revision->moderate_status_id = ModerateStatusModel::EDIT;
		$moderate_list_revision->list_name = $this->moderated_list_name;
		$moderate_list_revision->revision_number = $revision_number;
		$moderate_list_revision->save();

		if ($data) {
			foreach ($data as $v) {
				$model = $this->createModel();
				if ($this->fields) {
					foreach ($this->fields as $field_name) {
						$model->{$field_name} = $v->{$field_name};
					}
				}

				// Поля, которые являются условиями для выборки ревизии
				// заполняются отдельно (разные способы для составного условия - когда несколько полей, и для
				// выборки по идентификатору
				if (!is_array($revision_condition)) {
					$model->{$this->moderated_entity_name . '_id'} = $revision_condition;
				} else {
					foreach($revision_condition as $field_name => $field_value){
						$model->{$field_name} = $field_value;
					}
				}
				$model->revision_number = $revision_number;
				$model->save();
			}
		}

		return $this->getCurrentRevision($revision_condition);
	}

	/**
	 * @param $revision_condition
	 * @return ListRevision
	 */
	public function getCurrentRevision($revision_condition)
	{
		$moderate_list_revision_manager = new ModerateListRevisionManager();

		$revision_info = $moderate_list_revision_manager->getLastActiveByRevisionConditionAndListName($revision_condition,
			$this->moderated_list_name);

		if (!$revision_info)
			return $this->createNewRevision($revision_condition);
		else {
			$list_revision = new ListRevision();
			$list_revision->revision_info = $revision_info;
			$list_revision->elements = $this->getListByRevisionConditionAndRevisionNumber($revision_condition, $revision_info->revision_number);
			return $list_revision;
		}

	}

	public function publishRevision($entity_id)
	{
        $this->beforePublishRevision();

		$this->checkRevisionCondition($entity_id);

		$moderate_list_revision_manager = new ModerateListRevisionManager();
		$revision_info = $moderate_list_revision_manager->getLastActiveByRevisionConditionAndListName($entity_id,
			$this->moderated_list_name);

		if ($revision_info) {
			$data = $this->getListByRevisionConditionAndRevisionNumber($entity_id, $revision_info->revision_number);
			$manager = ModelManagerFactory::getByName($this->moderated_list_name);

			if (!is_array($entity_id)){
				$action = 'deleteBy' . $this->moderated_entity_name . 'Id';
				$manager->$action($entity_id);
			}
			else {
				$action = 'deleteBy';

				$keys = array();
				$values = array();

				foreach($entity_id as $key => $value){
					$keys[] = StringHelper::toCamelCase($key);
					$values[] = $value;
				}

				$action .= join('And', $keys);

				call_user_func_array(array($manager, $action), $values);
			}


			if ($data) {
				foreach ($data as $v) {
					$model = $manager->createModel();

					if ($this->fields) {
						foreach ($this->fields as $field_name) {
							$model->{$field_name} = $v->{$field_name};
						}
					}

					if (!is_array($entity_id))
					{
						$model->{$this->moderated_entity_name . '_id'} = $entity_id;
					} else {
						foreach($entity_id as $field_name => $field_value){
							$model->{$field_name} = $field_value;
						}
					}

					$model->save();
				}
			}

			$revision_info->moderate_status_id = ModerateStatusModel::PUBLISHED;
			$revision_info->save();
		}

        $this->afterPublishRevision($revision_info);
	}

    protected function beforePublishRevision()
    {

    }

    protected function afterPublishRevision()
    {

    }

	public function getListByRevisionConditionAndRevisionNumber($revision_condition, $revision_number)
	{
		$this->checkRevisionCondition($revision_condition);

		if (!is_array($revision_condition))
		{
			$data = $this->orm_model->select()->where($this->moderated_entity_name . '_id = ? AND revision_number = ?',
				$revision_condition,
				$revision_number)->fetchAll();
		} else {
			$where_string = '';
			foreach($revision_condition as $key => $value)
			{
				$where_string .= $key.' = "'.Register::get('db')->escape($value).'" AND ';
			}
			$where_string = preg_replace('/^(.+) AND $/', '$1', $where_string);
			$where_string .= ' AND revision_number = '.(int)$revision_number;
			$data = $this->orm_model->select()->where($where_string)->order('id ASC')->fetchAll();
		}

		return $this->initList($data);
	}

	public function getLastRevisionNumberByRevisionCondition(array $revision_condition)
	{
		$this->checkRevisionCondition($revision_condition);

		$moderate_list_revision_manager = new ModerateListRevisionManager();

		return $moderate_list_revision_manager->getLastRevisionNumberByRevisionConditionAndListName($revision_condition,
			$this->moderated_list_name);
	}

    public function getLastRevisionNumberByEntityId($entity_id)
    {
        throw new Exception('Данный метод не поддерживается');
    }


    public function getModeratedDataByRevisionCondition($entity_id)
	{
		$this->checkRevisionCondition($entity_id);

		$manager = ModelManagerFactory::getByName($this->moderated_list_name);

		if (!is_array($entity_id)) {
			$action = 'getListBy' . $this->moderated_entity_name . 'Id';
			$result = $manager->$action($entity_id);
		} else {
			$action = 'getListBy';

			$params = array();
			$values = array();
			foreach($entity_id as $key => $value)
			{
				$params[] = StringHelper::toCamelCase($key);
				$values[] = $value;
			}

			$action .= join('And', $params);

			$result = call_user_func_array(array($manager, $action), $values);
		}

		return $result;
	}

	public function clearListByEntityIdAndRevisionNumber($entity_id, $revision_number)
	{
		$this->checkRevisionCondition($entity_id);

		$sql = 'DELETE FROM ' . $this->table_name . '
					WHERE ' . $this->moderated_entity_name . '_id = ' . (int)$entity_id . '
						AND revision_number = ' . (int)$revision_number;

		$this->db->query($sql);
	}

	protected function generateEntityIdString($entity_id)
	{
		if (!is_array($entity_id))
			return $entity_id;

		$string = '';

		foreach($entity_id as $key => $value)
		{
			$string .= $key.'='.$value;
		}

		return $string;
	}

	public function processRevisionCondition($condition)
	{
		$this->checkRevisionCondition($condition);

		if (!$this->revision_conditions)
			return $condition;

		$result = array();

		foreach($this->revision_conditions as $field_name)
			$result[$field_name] = $condition[$field_name];

		return $result;
	}
}