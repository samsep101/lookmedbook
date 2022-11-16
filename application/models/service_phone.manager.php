<?php
	class ServicePhoneManager extends ModelManager
	{
		protected $table_name = 'service_phone';
		protected $model_name = 'ServicePhoneModel';

    /**
		 * @return ServicePhoneModel[]
		 */
		public function getListByPurpose($purpose)
		{
			$sql = 'SELECT *
                FROM ' . $this->table_name . '
                WHERE ' . $purpose . ' = 1';
			$data = $this->db->query($sql);

			return $this->initList($data);
		}
	}
