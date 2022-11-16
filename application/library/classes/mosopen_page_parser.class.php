<?php
	class MosopenPageParser extends PageParser
	{
		protected $session_manager;

		public function __construct($page_url = null)
		{
			$this->session_manager = MosopenSessionManager::getInstance();
			parent::__construct($page_url);
		}

		public function getPageContent($page_url)
		{
			return $this->session_manager->getRequest($page_url);
		}
	}