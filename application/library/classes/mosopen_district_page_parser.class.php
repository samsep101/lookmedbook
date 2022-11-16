<?php
	class MosopenDistrictPageParser extends MosopenPageParser
	{


		public function getName()
		{
			if (preg_match('/<title>([^\(]+)\([^\)]+\)/ims', $this->page_content, $matches))
			{
				return trim($matches[1]);
			} else {
				throw new Exception('Невозможно получить имя для страницы '.$this->page_url);
			}
		}

		public function getFormalName()
		{
			if (preg_match('/<title>[^\(]+\(([^\)]+)\)/ims', $this->page_content, $matches))
			{
				return trim($matches[1]);
			} else {
				throw new Exception('Невозможно получить имя для страницы '.$this->page_url);
			}
		}

		public function getRegionsPageLinks()
		{
			$start_position = mb_strpos($this->page_content, '<h3 id="regions_list">', null, 'utf-8');
			$length = mb_strlen($this->page_content, 'utf-8');
			$content = mb_substr($this->page_content, $start_position, $length, 'utf-8');

			if (preg_match_all('/<a href="(http:\/\/mosopen\.ru\/region\/[^"]+)"/ims', $content, $matches))
			{
				return $matches[1];
			} else {
				throw new Exception('Не удалось получить ссылки районов для '.$this->page_url);
			}
		}
	}