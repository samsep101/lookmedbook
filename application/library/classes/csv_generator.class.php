<?php
	class CsvGenerator
	{
		public function generateFromArray(array $data, $delimiter = ';')
		{
			$result = '';

			if ($data)
			{
				foreach($data as $row)
				{
					if ($row)
					{
						foreach($row as $cell)
						{
							$result .= $cell.$delimiter;
						}
					}
					$result = trim($result, $delimiter);
					$result .= "\r\n";
				}
			}

			$result = iconv('utf-8', 'cp1251', $result);
			return $result;
		}
	}