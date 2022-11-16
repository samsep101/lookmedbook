<?php
	class DistributionTaskManager extends ModelManager
	{
		protected $table_name = 'distribution_task';
		protected $model_name = 'DistributionTaskModel';

        /**
		 * @return DistributionTaskModel[]
		 */
		public function getListByDistributionId($distribution_id){
			$data = $this->orm_model->select()->where('distribution_id = ?', $distribution_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return DistributionTaskModel[]
		 */
		public function getListByAccountId($account_id){
			$data = $this->orm_model->select()->where('account_id = ?', $account_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return DistributionTaskModel[]
		 */
		public function getListByTaskStatusId($task_status_id){
			$data = $this->orm_model->select()->where('task_status_id = ?', $task_status_id)->fetchAll();
			return $this->initList($data);
		}

		public function checkExistsByDistributionIdAndAccountIdAndStatusId($distribution_id, $account_id, $task_status_id)
		{
			$data = $this->orm_model->select()->where('distribution_id = ? && account_id = ? && task_status_id = ?', $distribution_id, $account_id, $task_status_id)->fetchOne();
			return $this->initOne($data);
		}

		public function updateStatus($task_id, $status_id)
		{
			$sql = 'UPDATE distribution_task
                    SET task_status_id = ' . $status_id . '
                    WHERE id = ' . (int)$task_id;
			$db = Register::get('db');
			$db->query($sql);
		}

	}
