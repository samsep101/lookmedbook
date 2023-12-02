<?php
	class ElasticSearchLaboratoryIndexControl extends ElasticSearchModelIndexControl
	{
		public function __construct($indexName = null)
		{
			parent::__construct($indexName);
			$this->object_factory = new LaboratoryElasticSearchObjectsFactory();
		}


		/**
		 * Получение типа, с которым работает данный менеджер
		 *
		 * @return \Elastica\Type
		 */
		protected function getType()
		{
			return $this->getIndex()->getType('laboratory');
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
			 * @var LaboratorySearchParams $criteria
			 */
			$query = new \Elastica\Query\Match();

			$bool_filter = new \Elastica\Query\BoolQuery();

      if ($criteria->geo_point) {
        $point = array(
          'lat' => $criteria->geo_point->getLatitude(),
          'lon' => $criteria->geo_point->getLongitude()
        );
        $distance = $criteria->distance ? (string)(int) $criteria->distance : '1000';
        $match = new \Elastica\Query\GeoDistance('geo_point', $point, $distance . 'm');
        $bool_filter->addFilter($match);
      }

			if($criteria->urgent_tests)
			{
				$filter = new \Elastica\Query\Term();
				$filter->setTerm('is_has_urgent_tests', true);
        $bool_filter->addFilter($filter);
			}

			if($criteria->card_pay)
			{
				$filter = new \Elastica\Query\Term();
				$filter->setTerm('is_has_card_pay', true);
                $bool_filter->addFilter($filter);
			}

			if($criteria->work_seven_days)
			{
				$filter = new \Elastica\Query\Term();
				$filter->setTerm('is_work_seven_days', true);
        $bool_filter->addFilter($filter);
			}

			if($criteria->easy_entry)
			{
				$filter = new \Elastica\Query\Term();
				$filter->setTerm('is_easy_entry', true);
				$bool_filter->addFilter($filter);
			}

			if($criteria->without_turn)
			{
				$filter = new \Elastica\Query\Term();
				$filter->setTerm('is_without_turn', true);
        $bool_filter->addFilter($filter);
			}

			if($criteria->day_and_night)
			{
				$filter = new \Elastica\Query\Term();
				$filter->setTerm('is_day_and_night', true);
        $bool_filter->addFilter($filter);
			}

			if($criteria->city_id)
			{
				$filter = new \Elastica\Query\Term();
				$filter->setTerm('city', $criteria->city_id);
        $bool_filter->addFilter($filter);
			}

			$result_query = new \Elastica\Query();

      if ($criteria->geo_point) {

        $distance = (int) ($criteria->distance ? (string)(int) $criteria->distance : 1);

        $result_query->addSort([
          '_script' => [
            'type' => 'number',
            'script' => [
              'lang' => 'painless',
              'source' => '(doc[\'geo_point\'].arcDistance(' . (float)$criteria->geo_point->getLatitude() . ', ' . (float)$criteria->geo_point->getLongitude() . ')) <= ' . $distance .  ' ? 1 : 0'
            ],
            "order" => "desc",
          ]
        ]);
      }

			if($query->getParams())
			{
			    $bool_filter->addMust($query);
			}

			if($bool_filter->getParams())
			{
                $result_query->setQuery($bool_filter);
			}

			$this->addPaging($criteria, $result_query);
			$result_query->setStoredFields(['id']);

			return $result_query;
		}

        /**
         * Получение списка записей по координатам и дистанции
         *
         * @param GeoPoint $geo_point
         * @param $distance
         * @return array
         */
        public function getListByGeoPointAndDistance(GeoPoint $geo_point, $distance)
        {
            /**
             * @var LaboratorySearchParams $criteria
             */
            $bool_filter = new \Elastica\Query\BoolQuery();

            $location = array(
                'lat' => $geo_point->getLatitude(),
                'lon' => $geo_point->getLongitude()
            );
            $distance = (($distance)/1000).'km';
            $match = new \Elastica\Query\GeoDistance('geo_point', $location, $distance);
            $bool_filter->addFilter($match);

            $result_query = new \Elastica\Query();
            $result_query->setQuery($bool_filter);
            $result_query->setSize(1000);

            $data = $this->getType()->search($result_query);

            $result = array();
            foreach($data as $v)
            {
                /**
                 * @var \Elastica\Result $v
                 */
                $result[] = $v->getData();
            }


            return $result;
        }

	}
