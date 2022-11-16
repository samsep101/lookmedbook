<?php
	class MosopenRegionStreetsPageParser extends MosopenPageParser {

		public function getStreetNames()
		{
			$result = array();
			if (preg_match_all('/<a href="http:\/\/mosopen.ru\/street\/[0-9]+">([^<]+)<\/a>/ims', $this->page_content, $matches))
			{
				foreach($matches[1] as $street_name)
				{
					$street_name = trim($street_name);
					if (!preg_match('/^(.+) ([А-Яа-яЁё]+)$/u', $street_name, $street_match))
					{
						if (mb_strpos($street_name, 'Проектируемый проезд', null, 'utf-8') !== NULL)
						{
							$street_match = array(
								'',
								$street_name,
								'проезд'
							);
						}
					};

					$street_info = new StreetInfo();
					$street_info->type = trim($street_match[2]);
					$street_info->name = trim($street_match[1], ', ');

					$result[] = $street_info;
				}
			} else {
				throw new Exception('не получается получить список улиц для '.$this->page_url);
			}

			return $result;
		}
	}