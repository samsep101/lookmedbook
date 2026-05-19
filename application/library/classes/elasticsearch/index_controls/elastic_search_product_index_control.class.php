<?php

	class ElasticSearchProductIndexControl extends ElasticSearchModelIndexControl
	{

		public function __construct($indexName = null)
		{
			parent::__construct($indexName);
			$this->object_factory = new ProductElasticSearchObjectsFactory();
		}

		/**
		 * Получение типа, с которым работает данный менеджер
		 *
		 * @return \Elastica\Type
		 */
		protected function getType()
		{
			$type = $this->getIndex()->getType('product');


			//print_r($type);
			return $type;
		}

		/**
		 * Построение объекта запроса по критериям поиска
		 *
		 * @param ProductSearchCriteria $criteria
         *
		 * @return mixed
		 */
		protected function buildQueryObject(ModelSearchCriteria $criteria)
		{
			$query = new \Elastica\Query\Match();

            $bool_filter = new \Elastica\Query\BoolQuery();
			if($criteria->is_active)
			{
				$filter = new \Elastica\Query\Term();
				$filter->setTerm('is_active', true);

				$bool_filter->addFilter($filter);
			}

			if($criteria->full_name)
			{
        $name = preg_replace('/\-/', '\\-', $criteria->full_name);
				$query->setFieldQuery('full_name', $name);
				$query->setFieldOperator('full_name', 'AND');
			}

			if($criteria->product_categories)
			{
				$filter = new \Elastica\Query\Terms();
				$filter->setTerms('product_category', $criteria->product_categories);
				$bool_filter->addFilter($filter);
			}

			if($criteria->is_leader)
			{
				$filter = new \Elastica\Query\Term();
				$filter->setTerm('is_leader', true);
				$bool_filter->addFilter($filter);
			}

			if($criteria->manufacturer_id)
			{
				$filter = new \Elastica\Query\Term();
				$filter->setTerm('manufacturer', true);
				$bool_filter->addFilter($filter);
			}

			if($criteria->image_find_status_id)
			{
				$filter = new \Elastica\Query\Term();
				$filter->setTerm('image_find_status', $criteria->image_find_status_id);
				$bool_filter->addFilter($filter);
			}

			if($criteria->fill_information_status_id)
			{
				$filter = new \Elastica\Query\Term();
				$filter->setTerm('fill_information_status', $criteria->fill_information_status_id);
				$bool_filter->addFilter($filter);
			}

			if($criteria->product_itself)
			{
				$filter = new \Elastica\Query\Term();
				$filter->setTerm('id', $criteria->product_itself);
                $filter_no = new \Elastica\Query\BoolQuery();
                $filter_no->addMustNot($filter);
				$bool_filter->addFilter($filter_no);
			}

			$result_query = new \Elastica\Query();
			if($query->getParams())
			{
			    $bool_filter->addMust($query);
			}

			if($bool_filter->getParams())
			{
				$result_query->setQuery($bool_filter);
			}

			switch(!$criteria->full_name && $criteria->sort_by)
			{
				case 'name':
					$result_query->addSort([
						'full_name_sort' => [
							'order' => 'ASC',
                        ]
                    ]);
					break;
			}

			$this->addPaging($criteria, $result_query);
			$result_query->setStoredFields(['id']);

			return $result_query;
		}

	}
