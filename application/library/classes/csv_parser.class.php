<?php
	class CsvParser
	{
		private $file_name;
		private $delimiter = ',';

		public function __construct($file_name, $delimiter = ',')
		{
			$this->file_name = $file_name;
			$this->delimiter = $delimiter;
		}

		public function parse()
		{
			$result = array();

			if (($handle = fopen($this->file_name, 'r')) !== FALSE) {
				$i = 0;
				while (($line_array = fgetcsv($handle, 4000, $this->delimiter, '"')) !== FALSE) {
					for ($j = 0; $j < count($line_array); $j++) {
						$result[$i][$j] = $line_array[$j];
					}
					$i++;
				}
				fclose($handle);
			}

			return $result;
		}
	}