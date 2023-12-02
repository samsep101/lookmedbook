<?php
	class GeoCityManager extends ModelManager
	{
		protected $table_name = 'geo_city';
		protected $model_name = 'GeoCityModel';

    /**
		 * @return GeoCityModel
		 */
		public function getOneByCity($city)
		{
			$data = $this->orm_model->select()->where('city = ?', $city)->fetchOne();
			return count($data) ? $this->initOne($data) : null;
		}

    /**
		 * @return GeoCityModel[]
		 */
		public function getListByCity($query, $by_page, $page)
		{
			$offset = ($page - 1) * $by_page;
			$sql = 'SELECT *
                FROM geo_city
                WHERE city LIKE  "%' . $this->db->escape($query) . '%"
                LIMIT ' . $offset . ',' . $by_page;

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}
	}
