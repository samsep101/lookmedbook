<?php

	class ProductIndexCommand extends ModelIndexCommand
	{
		public function __construct()
		{
			$this->model_manager = new SearchIndexProductManager();
			$this->index_manager = new ElasticSearchProductIndexControl();
		}

	}