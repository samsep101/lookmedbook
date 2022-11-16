<?php

	class DiseaseElasticSearchFormatter implements IElasticSearchFormatter
	{
		/**
		 * @param DynamicModel $model
		 *
		 * @return \Elastica\Document
		 */
		public function toElasticSearchView(DynamicModel $model)
		{
			/**
			 * @var DiseaseModel $model
			 */
			$result = array();

			$result['id'] = $model->getId();


			$result['name'] = $model->title;

			$alt_names = array();

			if($model->alt_names)
			{
				foreach($model->alt_names as $alt_name)
				{
					$alt_names[] = trim($alt_name->alt_name);
				}
			}

			$result['alt_name'] = $alt_names;
			$result['is_active'] = (bool)$model->is_active;

			$result['tags'] = $model->tags;


			return new \Elastica\Document($model->getId(), $result);
		}

	}
