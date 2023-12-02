<?php
	class LaboratoryIndexCommand extends ModelIndexCommand
	{
		function __construct()
		{
			$this->model_manager = new SearchIndexLaboratoryManager();
			$this->index_manager = new ElasticSearchLaboratoryIndexControl();
		}

	}