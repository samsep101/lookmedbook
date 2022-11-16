<?php
	class DoctorSearchQueryTaskManager extends ModelManager
	{
		protected $table_name = 'doctor_search_query_task';
		protected $model_name = 'DoctorSearchQueryTaskModel';

        protected $insert_type = 'delayed';

        /**
		 * @return DoctorSearchQueryTaskModel[]
		 */
		public function getListByTaskStatusId($task_status_id)
		{
			$data = $this->orm_model->select()->where('task_status_id = ?', $task_status_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return DoctorSearchQueryTaskModel
		 */
		public function getOneByHash($hash)
		{
			$data = $this->orm_model->select()->where('hash = ?', $hash)->fetchOne();
			return $this->initOne($data);
		}

        /**
		 * @return DoctorSearchQueryTaskModel
		 */
		public function getOneByTaskStatusId($task_status_id)
		{
			$data = $this->orm_model->select()->where('task_status_id = ?', $task_status_id)->fetchOne();
			return (isset($data)) ? $this->initOne($data) : null;
		}

		public function deleteOneByHash($hash)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
                    WHERE  hash="' . $this->db->escape($hash) . '"';
			$this->db->query($sql);
		}

		public function setTaskStatusIdById($task_id, $task_status_id)
		{
			$sql = 'UPDATE ' . $this->table_name . '
                    SET task_status_id = ' . (int)$task_status_id . '
                    WHERE id = ' . (int)$task_id;

			$this->db->query($sql);
		}

		public function resetAllTasks()
		{
			$sql = 'UPDATE `' . $this->table_name . '`
                    SET task_status_id = ' . (int)TaskStatusModel::IN_QUEUE;
			$this->db->query($sql);
		}
	}
