<?php
	class ClinicSearchShowManager extends ModelManager
	{
		protected $table_name = 'clinic_search_show';
		protected $model_name = 'ClinicSearchShowModel';

		public function getMaxBallsByHash($hash)
		{
			$sql = 'SELECT MAX(balls) as result
                    FROM `' . $this->table_name . '`
                    WHERE hash = "' . $this->db->escape($hash) . '"';

			$data = $this->db->query($sql);

			return (int)$data[0]['result'];
		}

		public function deleteByHash($hash)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
                    WHERE hash = "' . $this->db->escape($hash) . '"';

			$this->db->query($sql);
		}

    /**
		 * @return ClinicSearchShowModel
		 */
		public function getOneByHash($hash)
		{
			$data = $this->orm_model->select()->where('hash = ?', $hash)->fetchOne();
			return (isset($data)) ? $this->initOne($data) : null;
		}

		/**
		 * Получение докторов, которые должны отображаться на первых
		 * 4 позиция
		 * @params $hash
		 * @params $limit
		 */
		public function getPrimaryIdListByHashWithLimit($hash, $limit)
		{
			$sql = 'SELECT *
                    FROM `' . $this->table_name . '`
                    WHERE hash = "' . $this->db->escape($hash) . '"
                    ORDER BY balls ASC
                    LIMIT ' . (int)$limit;

			$data = $this->db->query($sql);

			$result = array();

			$balls = 4;
			foreach($data as $v)
			{
				$this->addBallsById($v['id'], $balls);
				$result[] = $v['clinic_id'];
				$balls--;
			}

			return $result;
		}

		private function addBallsById($id, $shows)
		{
			$sql = 'UPDATE `' . $this->table_name . '`
                    SET balls = balls + ' . (int)$shows . '
                    WHERE id = ' . (int)$id;

			$this->db->query($sql);
		}
	}
