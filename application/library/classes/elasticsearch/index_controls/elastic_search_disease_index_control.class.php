<?php
	class ElasticSearchDiseaseIndexControl extends ElasticSearchModelIndexControl
	{

		public function __construct($indexName = null)
		{
			parent::__construct($indexName);
			$this->object_factory = new DiseaseElasticSearchObjectsFactory();
		}

		/**
		 * Получение типа, с которым работает данный менеджер
		 *
		 * @return \Elastica\Type
		 */
		protected function getType()
		{
			return $this->getIndex()->getType('disease');
		}

		/**
		 * Построение объекта запроса по критериям поиска
		 *
		 * @param ModelSearchCriteria $criteria
		 *
		 * @return mixed
		 */
		protected function buildQueryObject(ModelSearchCriteria $criteria)
		{
			/**
			 * @var DiseaseSearchCriteria $criteria
			 */
			$bool_query = new \Elastica\Query\BoolQuery();

			if($criteria->is_active)
			{
				$filter = new \Elastica\Query\Term();
				$filter->setTerm('is_active', true);
                $bool_query->addFilter($filter);
			}

			if($criteria->name)
			{
				$query = new \Elastica\Query\MultiMatch();
				$query->setQuery($criteria->name);
				$query->setFields(['name', 'alt_name']);
				$query->setType('most_fields');
				$query->setOperator(\Elastica\Query\MultiMatch::OPERATOR_AND);
				$query->setMinimumShouldMatch("80%");
				$bool_query->addMust($query);
			}

			if ($criteria->tag) {
			  $query = new \Elastica\Query\Match();
			  $query->setFieldQuery('tags', $criteria->tag);
			  $query->setFieldOperator('tags', \Elastica\Query\MultiMatch::OPERATOR_AND);
        $bool_query->addMust($query);
      }

			$result_query = new \Elastica\Query();
			if($bool_query->getParams())
			{
				$result_query->setQuery($bool_query);
			}
			$this->addPaging($criteria, $result_query);
			$result_query->setStoredFields(['id']);

			return $result_query;
		}

	}
