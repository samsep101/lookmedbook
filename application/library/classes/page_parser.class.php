<?php
	class PageParser {
		protected  $page_url;
		protected  $page_content;

		public function __construct($page_url = ''){
			if ($page_url)
				$this->setPageUrl($page_url);
		}

		protected function getPageContent($page_url)
		{
			return file_get_contents($page_url);
		}

		public function setPageUrl($page_url)
		{
			$this->page_url = $page_url;
			$this->page_content = $this->getPageContent($page_url);
		}
	}