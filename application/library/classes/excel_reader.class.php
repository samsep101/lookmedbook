<?php
	require_once( ABS_ROOT . '/application/library/third_party/PhpExcel/PHPExcel.php');

	class ExcelReader
	{
		private $filepath;
		private $sheet_name;

		public function __construct($file_path, $sheet_name = '')
		{
			$this->filepath = $file_path;
			$this->sheet_name = $sheet_name;
		}

		public function readToArray()
		{
			$result = array();
			$iterator = new ExcelReadIterator($this->filepath, $this->sheet_name);

			foreach ($iterator as $row)
			{
				$result[] = $row;
			}

			return $result;
		}

	}