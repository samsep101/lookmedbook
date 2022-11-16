<?php

	class DiseaseIndexCommand extends ModelIndexCommand
	{
		public function __construct()
		{
			$this->model_manager = new SearchIndexDiseaseManager();
			$this->index_manager = new ElasticSearchDiseaseIndexControl();
		}
	}