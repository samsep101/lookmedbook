<?php
	class HelpMaterialManager extends ModelManager
	{
		protected $table_name = 'help_material';
		protected $model_name = 'HelpMaterialModel';

		public function getActiveListBySubrubricId($help_subrubric_id)
		{
			$db = Register::get('db');

			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE `help_subrubric_id` = "' . (int)$help_subrubric_id . '"
                    AND `is_active` = 1';

			$data = $db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

		public function getSubrubricIdById($id)
		{
			$data = $this->orm_model->select()->where('id = ?', $id)->fetchOne();
			return count($data) ? $data['help_subrubric_id'] : null;
		}

		public function getActiveList()
		{
			$data = $this->orm_model->select()->where('is_active = ?', 1)->fetchAll();
			return (count($data)) ? $this->initList($data) : array();
		}

		public function getActiveListByTitleOrContent($query, $by_page, $page, $get_extra_entry = 0)
		{
			$offset = ($page - 1) * $by_page;
			if($get_extra_entry)
			{
				$by_page++;
			}
			$sql = 'SELECT help_material.*, help_subrubric.title as sub_title, help_subrubric.content as sub_content,
                    help_rubric.title as rub_title, help_rubric.content as rub_content
                    FROM help_material
                    LEFT JOIN help_subrubric ON help_material.help_subrubric_id = help_subrubric.id
                    LEFT JOIN help_rubric ON help_subrubric.help_rubric_id = help_rubric.id
                    WHERE (help_material.title LIKE  "%' . $this->db->escape($query) . '%"
                    OR help_subrubric.title LIKE  "%' . $this->db->escape($query) . '%"
                    OR help_rubric.title LIKE  "%' . $this->db->escape($query) . '%"
                    OR help_material.content LIKE "%' . $this->db->escape($query) . '%")
                    AND help_material.is_active = 1
                    AND help_subrubric.is_active = 1
                    AND help_rubric.is_active = 1
                    LIMIT ' . $offset . ',' . $by_page;

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}
	}