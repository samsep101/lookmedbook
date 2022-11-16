<?php
	class ServiceEmailManager extends ModelManager
	{
		protected $table_name = 'service_email';
		protected $model_name = 'ServiceEmailModel';

		/**
		 * @var string $purpose
		 * @return ServiceEmailModel[]
		 */
		public function getListByPurpose($purpose)
		{
			$sql = 'SELECT *
					FROM ' . $this->table_name . '
					WHERE ' . $purpose . ' = 1';
			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		public function getNewVisitNotificationList()
		{
			$data = $this->orm_model->select()->where('is_record = 1')->fetchAll();
			return $this->initList($data);
		}

		public function getCancelVisitNotificationNotificationList()
		{
			$data = $this->orm_model->select()->where('is_cancel = 1')->fetchAll();
			return $this->initList($data);
		}
	}
