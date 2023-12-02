<?php
    class ModelFilesGenerator
    {
		/**
		 * @var DbScheme
		 */
		private $db_scheme;
        private $models_folder = 'application/models/';
        private $model_generator;
        private $manager_generator;

		private $manager_class_name_prefix = '';
		private $model_class_name_prefix = '';
		private $manager_base_class_name = 'ModelManager';
		private $model_base_class_name = 'DynamicModel';

		private $fields;

		/**
		 * @var Db
		 */
		private $db;

        public function __construct()
        {
            $this->model_generator = new ModelFileGenerator();
            $this->manager_generator = new ManagerFileGenerator();
        }

		public function getDb()
		{
			if(!$this->db)
				$this->db = Register::get('db');

			return $this->db;
		}

		public function getModelGenerator()
		{
			return $this->model_generator;
		}

		public function setFields($fields)
		{
			$this->fields = $fields;
		}

		public function getManagerGenerator()
		{
			return $this->manager_generator;
		}

		public function setModelBaseClassName($model_base_class_name)
		{
			$this->model_base_class_name = $model_base_class_name;
		}

		public function setManagerClassNamePrefix($manager_class_name_prefix)
		{
			$this->manager_class_name_prefix = $manager_class_name_prefix;
		}

		public function setModelClassNamePrefix($model_class_name_prefix)
		{
			$this->model_class_name_prefix = $model_class_name_prefix;
		}

		public function setManagerBaseClassName($manager_base_class_name)
		{
			$this->manager_base_class_name = $manager_base_class_name;
		}

		public function setDb($db)
		{
			$this->db = $db;
		}

        public function generate()
        {
            $this->getDbScheme();

			$this->model_generator->setBaseClassName($this->model_base_class_name);
			$this->model_generator->setClassNamePrefix($this->model_class_name_prefix);

			$this->manager_generator->setModelClassNamePrefix($this->model_class_name_prefix);
			$this->manager_generator->setClassNamePrefix($this->manager_class_name_prefix);
			$this->manager_generator->setBaseClassName($this->manager_base_class_name);

            foreach ($this->db_scheme->getTables() as $table) {
				/**
				 * @var TableInfo $table
				 */
				$model_file_name = $table->getTableName().'.model.php';
				if ($this->model_class_name_prefix)
				{
					$model_file_name = strtolower($this->model_class_name_prefix).'_'.$model_file_name;
				}
				$model_file_path = $this->models_folder . $model_file_name;
                if (!file_exists($model_file_path)) {
					$this->model_generator->setFields($this->db->getTableFields($table->getTableName()));
                    $this->model_generator->generate($table->getTableName(), $model_file_path);
                }

				$manager_file_name = $table->getTableName().'.manager.php';
				if ($this->manager_class_name_prefix)
				{
					$manager_file_name = strtolower($this->manager_class_name_prefix).'_'.$manager_file_name;
				}
				$manager_file_path = $this->models_folder . $manager_file_name;

                if (!file_exists($manager_file_path)) {
                    $this->manager_generator->generate($table, $manager_file_path);
                }
            }

            return;
        }


        private function getDbScheme()
        {
            $this->db_scheme = new DbScheme();
            foreach ($this->getTableNames() as $table_name) {
                $table_info = new TableInfo($table_name);
                $this->db_scheme->addTable($table_info);

                $fields = $this->getTableFields($table_name);

                foreach ($fields as $field) {
                    $field_info = new FieldInfo();

                    $field_info->setName($field['Field']);
                    if (preg_match('/^(.+)_id$/', $field['Field'], $matches)) {
                        $field_info->setForeignKey(true);
                        $field_info->setForeignName($matches[1]);
                    }

                    if ($field['Key'] == 'MUL') {
                        $field_info->setIsIndex(true);
                    }

                    if ($field['Key'] == 'UNI') {
                        $field_info->setIsUnique(true);
                    }

                    $table_info->addField($field_info);
                }
            }
        }

        private function getTableNames()
        {
            return $this->getDb()->getTablesList();
        }

        private function getTableFields($table_name)
        {
            return DbHelper::getTableFields($table_name);
        }
    }