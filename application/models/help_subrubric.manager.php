<?php
	class HelpSubrubricManager extends ModelManager
	{
		protected $table_name = 'help_subrubric';
		protected $model_name = 'HelpSubrubricModel';

		public function getActiveListByRubricId($help_rubric_id)
		{
			$db = Register::get('db');

			$sql = 'SELECT *
                    FROM help_subrubric
                    WHERE `help_rubric_id` = "' . $this->db->escape($help_rubric_id) . '"
                    AND `is_active` = 1';

			$data = $db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

		public function getRubricIdById($id)
		{
			$data = $this->orm_model->select()->where('id = ?', $id)->fetchOne();
			return count($data) ? $data['help_rubric_id'] : false;
		}

		public function getActiveList()
		{
			$data = $this->orm_model->select()->where('is_active = ?', 1)->fetchAll();
			return (count($data)) ? $this->initList($data) : array();
		}
	}