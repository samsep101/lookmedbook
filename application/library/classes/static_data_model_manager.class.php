<?php
	class StaticDataModelManager extends ModelManager
	{
		protected $model_data = array();

		public function getOneById($id)
		{
			return (isset($this->model_data[$id])) ? $this->initOne($this->model_data[$id]) : NULL;
		}

		public function getList()
		{
			return $this->initList($this->model_data);
		}

		public function getListBySearchParams($criteria)
		{
			return $this->getList();
		}
	}