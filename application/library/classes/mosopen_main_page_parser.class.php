<?php
	class MosopenMainPageParser extends MosopenPageParser
	{
		public function __construct()
		{
			parent::__construct('http://mosopen.ru/');
		}

		public function getDistrictsPageLinks()
		{
			if (preg_match_all('/<a href="(http:\/\/mosopen\.ru\/district\/[^"]+)"/ims', $this->page_content, $matches))
			{
				return $matches[1];
			} else {
				throw new Exception('Не удалось получить список округов');
			}
		}
	}