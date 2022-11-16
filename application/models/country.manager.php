<?php
	class CountryManager extends ModelManager
	{
		protected $table_name = 'country';
		protected $model_name = 'CountryModel';

		public function getIdByName($name)
		{
			$data = $this->orm_model->select()->where('name=?', $this->db->escape($name))->fetchOne();
			return count($data) ? $data['id'] : null;
		}
	}