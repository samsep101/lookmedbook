<?php
	class DiseaseUnderstandManager extends ModelManager
	{
		protected $table_name = 'disease_understand';
		protected $model_name = 'DiseaseUnderstandModel';

		public function checkExistsByDiseaseIdAndAccountId($disease_id, $account_id)
		{
			$sql = 'SELECT COUNT(*) as `result`
                    FROM ' . $this->table_name . '
                    WHERE `disease_id` = ' . (int)$disease_id . '
                    AND `account_id` = ' . (int)$account_id;

			$data = $this->db->query($sql);

			return (bool)$data[0]['result'];
		}

		/**
		 * @param $account_id
		 * @param $disease_id
		 *
		 * @return DiseaseUnderstandModel
		 */
        /**
		 * @return DiseaseUnderstandModel
		 */
		public function getOneByAccountIdAndDiseaseId($account_id, $disease_id)
		{
			$data = $this->orm_model->select()->where('account_id = ? AND disease_id = ?', $account_id, $disease_id)->fetchOne();
			return ($data) ? $this->initOne($data) : null;
		}

		public function getCountByDiseaseId($disease_id)
		{
			$sql = 'SELECT COUNT(*) as `result`
                    FROM ' . $this->table_name . '
                    WHERE `disease_id` = ' . (int)$disease_id;

			$data = $this->db->query($sql);

			return (isset($data[0]['result'])) ? $data[0]['result'] : false;
		}

		public function getCountByDiseaseIdAndUnderstandFlag($disease_id, $understand_flag)
		{
			$sql = 'SELECT COUNT(*) as `result`
                    FROM ' . $this->table_name . '
                    WHERE disease_id = ' . (int)$disease_id . '
                    AND understand_flag = ' . (int)$understand_flag;

			$data = $this->db->query($sql);

			return (isset($data[0]['result'])) ? $data[0]['result'] : false;
		}

	}
