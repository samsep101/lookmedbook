<?php
	class MosopenRegionPageParser extends MosopenPageParser
	{
		public function getName()
		{
			if(preg_match('/<div class="breadcrumbs">.*&nbsp;\/[\r\n\t ]+([А-Яа-яЁё \-]+)[\r\n\t ]+<\/div>/imsu', $this->page_content, $matches))
			{
				return trim($matches[1]);
			} else {
				throw new Exception('Не получается получить имя для '.$this->page_url);
			}
		}

		public function getStreets()
		{
			$mosopen_street_page_parser = new MosopenRegionStreetsPageParser();
			$street_page_url = $this->getStreetPageUrl();
			$mosopen_street_page_parser->setPageUrl($street_page_url);

			$streets = $mosopen_street_page_parser->getStreetNames();

			return $streets;
		}

		public function getMetroStations()
		{
			if (preg_match_all('/<a href="http:\/\/mosopen\.ru\/metro\/station\/[^"]+"[^>]+>([^<]+)<\/a>/ims', $this->page_content, $matches))
			{
				return $matches[1];
			} else {
				return array();
			}
		}

		public function getStreetPageUrl()
		{
			if (preg_match('/<a href="([^"]+)"[^>]+>Улицы района/ims', $this->page_content, $matches)){
				return $matches[1];
			} else {
				throw new Exception('не удается получить ссылку для '.$this->page_url);
			}
		}
	}