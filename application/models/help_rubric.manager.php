<?php
	class HelpRubricManager extends ModelManager
	{
		protected $table_name = 'help_rubric';
		protected $model_name = 'HelpRubricModel';

		public function getActiveList()
		{
			$data = $this->orm_model->select()->where('is_active = ?', 1)->fetchAll();
			return (count($data)) ? $this->initList($data) : array();
		}
	}