<?php
	class VidalModelManager extends ModelManager
	{
		public function __construct()
		{
			parent::__construct();
			$this->db = new Db(DB_VIDAL_HOST, DB_VIDAL_USER, DB_VIDAL_PASSWORD, DB_VIDAL_NAME);
			$this->orm_model->setDb($this->db);
		}
	}