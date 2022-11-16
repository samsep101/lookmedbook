<?php
	class ClinicSearchQueryTaskManager extends ModelManager
	{
		protected $table_name = 'clinic_search_query_task';
		protected $model_name = 'ClinicSearchQueryTaskModel';

        /**
		 * @return ClinicSearchQueryTaskModel[]
		 */
		public function getListByTaskStatusId($task_status_id)
		{
			$data = $this->orm_model->select()->where('task_status_id = ?', $task_status_id)->fetchAll();
			return (isset($data)) ? $this->initList($data) : array();
		}

        /**
		 * @return ClinicSearchQueryTaskModel
		 */
		public function getOneByHash($hash)
		{
			$data = $this->orm_model->select()->where('hash = ?', $hash)->fetchOne();
			return (isset($data)) ? $this->initOne($data) : null;
		}

        /**
		 * @return ClinicSearchQueryTaskModel
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
