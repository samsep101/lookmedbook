<?php

	class SettingsManager extends ModelManager
	{
		protected $table_name = 'settings';
		protected $model_name = 'SettingsModel';

		public static function get($code)
		{
			/**
			 * @var SettingsManager $manager
			 */
			$manager = ModelManagerFactory::getByName('settings');
			return $manager->getValueByCode($code);
		}

		/**
		 * @param $code
		 *
		 * @return string
		 */
		public function getValueByCode($code)
		{
			$data = $this->orm_model->select()->where("code = ? ", $code)->fetchOne();
			return ($data['value']) ? $data['value'] : null;
		}

		/**
		 * @return SettingsModel
		 */
		public function getOneByCode($code)
		{
			$sql = 'SELECT ' . $this->selected_fields . '
                    FROM ' . $this->table_name . '
                    WHERE code = "' . $this->db->escape($code) . '"';
			$db = Register::get('db');
			$data = $db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}
	}
