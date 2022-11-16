<?php
	class ControllerManager extends ModelManager
	{
		protected $table_name = 'controller';
		protected $model_name = 'ControllerModel';

        /**
		 * @return ControllerModel
		 */
		public function getOneByCode($controllerCode)
		{
			$sql = 'SELECT *
                    FROM controller
                    WHERE `code` = "' . $this->db->escape($controllerCode) . '"';

			$data = $this->db->query($sql);
			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

		public function getActiveList()
		{
			$data = $this->orm_model->select()->where('is_active = 1')->fetchAll();
			return (isset($data)) ? $this->initList($data) : array();
		}
	}
