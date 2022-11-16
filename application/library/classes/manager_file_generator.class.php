<?php
    class ManagerFileGenerator extends SystemFileGenerator
    {
		protected $class_name_prefix = '';
		protected $model_class_name_prefix = '';

		protected $base_class_name = 'ModelManager';

		public function setBaseClassName($base_class_name)
		{
			$this->base_class_name = $base_class_name;
		}

		public function setClassNamePrefix($class_name_prefix)
		{
			$this->class_name_prefix = $class_name_prefix;
		}

		public function setModelClassNamePrefix($model_class_name_prefix)
		{
			$this->model_class_name_prefix = $model_class_name_prefix;
		}

        public function generate(TableInfo $table, $filepath)
        {
            $fields = $table->getFields();
            $table_name = $table->getTableName();
            $class_name = $this->getCamelCaseString($table->getTableName());

            $methods = array();
            foreach($fields as $field){
                if ($field->getIsIndex()){
                    $methods[] = array(
                        'type' => 'List',
                        'field_camel_name' =>  $this->getCamelCaseString($field->getName()),
                        'field_name' => $field->getName()
                    );
                }
            }


            $html  = "<?php\r\n";
			$html .= "\tclass ".$this->class_name_prefix.$class_name."Manager extends ".$this->base_class_name."\r\n\t{\r\n";
			$html .= "\t\t".'protected $table_name = "'.$table_name.'";'."\r\n";
			$html .= "\t\t".'protected $model_name = "'.$this->model_class_name_prefix.$class_name.'Model";'."\r\n";
			$html .= "\r\n";

			if ($methods)
			{
				foreach($methods as $method)
				{
					$field_name = $method['field_name'];
        			$field_camel = $method['field_camel_name'];

					if ($method['type'] == 'List')
					{
						$html .= "\r\n";
						$html .= "\t\t/**\r\n";
						$html .= "\t\t * @var int $".$field_name."\r\n";
						$html .= "\t\t * @return ".$this->model_class_name_prefix.$class_name.'Model[]'."\r\n";
						$html .= "\t\t */\r\n";
						$html .= "\t\tpublic function getListBy".$field_camel.'($'.$field_name.')'."\r\n\t\t".'{'."\r\n";
						$html .= "\t\t\t".'$data = $this->orm_model->select()->where(\''.$field_name.' = ?\', $'.$field_name.')->fetchAll();'."\r\n";
						$html .= "\t\t\t".'return $this->initList($data);'."\r\n";
						$html .= "\t\t".'}'."\r\n";
					}

					if ($method['type'] == 'One')
					{
						$html .= "\r\n";
						$html .= "\t\t/**\r\n";
						$html .= "\t\t * @var int $".$field_name."\r\n";
						$html .= "\t\t * @return ".$this->model_class_name_prefix.$class_name.'Model'."\r\n";
						$html .= "\t\t */\r\n";
						$html .= "\t\tpublic function getOneBy".$field_camel.'($'.$field_name.')'."\r\n\t\t".'{'."\r\n";
						$html .= "\t\t\t".'$data = $this->orm_model->select()->where(\''.$field_name.' = ?\', $'.$field_name.')->fetchOne();'."\r\n";
						$html .= "\t\t\t".'return $this->iniOne($data);'."\r\n";
						$html .= "\t\t".'}'."\r\n";
					}
				}
			}

			$html .= "\r\n\t}";

            file_put_contents($filepath, $html);

            return;
        }

    }