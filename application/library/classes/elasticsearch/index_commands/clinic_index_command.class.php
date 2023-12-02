<?php

	class ClinicIndexCommand extends ModelIndexCommand
	{
		public function __construct()
		{
			$this->model_manager = new SearchIndexClinicManager();
			$this->index_manager = new ElasticSearchClinicIndexControl();
		}
	}