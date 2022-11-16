<?php
	class DistributionManager extends ModelManager
	{
		protected $table_name = 'distribution';
		protected $model_name = 'DistributionModel';


        /**
		 * @return DistributionModel[]
		 */
		public function getListByDistributionTypeId($distribution_type_id){
			$data = $this->orm_model->select()->where('distribution_type_id = ?', $distribution_type_id)->fetchAll();
			return $this->initList($data);
		}
                                                        
        /**
		 * @return DistributionModel[]
		 */
		public function getListByTaskStatusId($task_status_id){
			$data = $this->orm_model->select()->where('task_status_id = ?', $task_status_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return DistributionModel[]
		 */
		public function getListByStartDateAndStatus($dt,$status_id){
			$data = $this->orm_model->select()->where('dt_start = ? and task_status_id=?', $dt, $status_id)->fetchAll();
			return $this->initList($data);
		}

		public function updateStatus($distribution_id, $status_id)
		{
			$sql = 'UPDATE distribution
                    SET task_status_id = ' . $status_id . '
                    WHERE id = ' . (int)$distribution_id;
			$db = Register::get('db');
			$db->query($sql);
		}
	}
