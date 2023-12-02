<?php
    class ModelFileGenerator extends SystemFileGenerator
    {
		private $base_class_name = 'DynamicModel';
		private $class_name_prefix = '';

		private $fields = array();

		public function setBaseClassName($base_class_name)
		{
			$this->base_class_name = $base_class_name;
		}

		public function setClassNamePrefix($class_name_prefix)
		{
			$this->class_name_prefix = $class_name_prefix;
		}

		public function setFields($fields)
		{
			$this->fields = $fields;
		}

        public function generate($table_name, $filename)
        {
            $class_name = $this->getCamelCaseString($table_name);

            $str = "<?php\r\n";

			$comments = ModelCommentsGenerator::generate($this->fields);
			$str .= $comments;
            $str .= "\r\n\tclass ".$this->class_name_prefix.$class_name.'Model extends DynamicModel {'."\r\n\r\n";
			$str .= "\t}";

            file_put_contents($filename, $str);

            return;
        }
    }