<?php
	class FiasManager extends ModelManager
	{
		protected $table_name = 'addrobj';
		protected $model_name = 'FiasModel';

		public function __construct()
		{
			parent::__construct();

			$this->db = new Db('localhost', 'root', '123', 'kladr');
		}

	}