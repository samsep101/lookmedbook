<?php

class ElasticSearchClinicIndexControl extends ElasticSearchModelIndexControl
{

  public function __construct($indexName = null)
  {
    parent::__construct($indexName);
    $this->object_factory = new ClinicElasticSearchObjectsFactory();
  }

  /**
   * Получение типа, с которым работает данный менеджер
   *
   * @return \Elastica\Type
   */
  protected function getType()
  {
    return $this->getIndex()->getType('clinic');
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
     * @var ClinicSearchParams $criteria
     */
    $filter = new \Elastica\Query\BoolQuery();
    $query = new \Elastica\Query\Match();

    if ($criteria->specialty_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('specialties.id', $criteria->specialty_id);
      $filter->addFilter($match);
    }

    if ($criteria->services) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('services.id', $criteria->services);
      $filter->addFilter($match);
    }

    if ($criteria->service_categories) {
        if (is_array($criteria->service_categories)) {
            $match = new \Elastica\Query\Terms();
            $match->setTerms('service_categories.id', $criteria->service_categories);
        } else {
            $match = new \Elastica\Query\Term();
            $match->setTerm('service_categories.id', $criteria->service_categories);
        }
      $filter->addFilter($match);
    }

    if ($criteria->types) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('types.id', $criteria->types);
      $filter->addFilter($match);
    }

    if ($criteria->city_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('city', (int)$criteria->city_id);
      $filter->addFilter($match);
    }

    if ($criteria->primary_clinic_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('primary_clinic_id', (int) $criteria->primary_clinic_id);
      $filter->addFilter($match);
    }

    if ($criteria->specialization_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('specializations', $criteria->specialization_id);
      $filter->addFilter($match);
    }

    if ($criteria->purpose_of_visit_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('specialties.purposes_of_visit', $criteria->purpose_of_visit_id);
      $filter->addFilter($match);
    }

    if ($criteria->children) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('is_children', TRUE);
      $filter->addFilter($match);
    }

    if ($criteria->handicapped) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('is_handicapped', TRUE);
      $filter->addFilter($match);
    }

    if ($criteria->pregnant) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('is_pregnant', TRUE);
      $filter->addFilter($match);
    }

    if ($criteria->day_and_night) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('is_day_and_night', TRUE);
      $filter->addFilter($match);
    }

    if ($criteria->only_children) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('only_children', TRUE);
      $filter->addFilter($match);
    } else if (is_bool($criteria->only_children) && $criteria->only_children === FALSE) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('only_children', FALSE);
      $filter->addFilter($match);
    }

    if ($criteria->clinic_name) {
      $query->setFieldQuery('name', $criteria->clinic_name);
      $query->setFieldOperator('name', 'AND');
      $query->setFieldMinimumShouldMatch('name','40%');
    }

    if ($criteria->doctor_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('doctors', $criteria->doctor_id);
      $filter->addFilter($match);
    }

    if ($criteria->metro_station_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('metro_station_ids.id', $criteria->metro_station_id);
      $filter->addFilter($match);
    }
    if ($criteria->is_active) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('is_active', TRUE);
      $filter->addFilter($match);
    }

    if ($criteria->not_show_example) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('is_not_example', TRUE);
      $filter->addFilter($match);
    }

    if ($criteria->district_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('district', $criteria->district_id);
      $filter->addFilter($match);
    }

    if ($criteria->region_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('region', $criteria->region_id);
      $filter->addFilter($match);
    }

    if ($criteria->street_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('street', $criteria->street_id);
      $filter->addFilter($match);
    }
    
     if ($criteria->geo_point && !$criteria->nearest) {
         $location = array(
             'lat' => $criteria->geo_point->getLatitude(),
             'lon' => $criteria->geo_point->getLongitude()
         );
         $distance = $criteria->distance.'m';
         $match = new \Elastica\Query\GeoDistance('geo_point', $location, $distance);
         $filter->addFilter($match);
     }

    if ($criteria->registry_user_id) {
      /**
       * @var UserManager $user_manager
       * @var UserModel $user
       */
      $user_manager = ModelManagerFactory::getByName('user');
      $user = $user_manager->getOneById($criteria->registry_user_id);

      $roles = array(RoleModel::ACCOUNT_MANAGER, RoleModel::ACCOUNT_REGISTRY, RoleModel::FREELANCE_MANAGER);

      if (in_array($user->role_id, $roles)) {
          $match = new \Elastica\Query\Term();
        $match->setTerm('registry_users', $criteria->registry_user_id);
        $filter->addFilter($match);
      }
    }

    if ($criteria->freelancer_id) {
        $match = new \Elastica\Query\Term();
      $match->setTerm('freelancers', $criteria->freelancer_id);
      $filter->addFilter($match);
    }

    if ($criteria->is_region) {
        $match = new \Elastica\Query\Term();
      $match->setTerm('is_region', TRUE);
      $filter->addFilter($match);
    }

    if ($criteria->status) {
        $match = new \Elastica\Query\Term();
      $match->setTerm('status', $criteria->status);
      $filter->addFilter($match);
    }

    if ($criteria->regions) {
        $match = new \Elastica\Query\Term();
      $match->setTerm('region', $criteria->regions);
      $filter->addFilter($match);
    }

    if ($criteria->twenty_four_hours) {
        $match = new \Elastica\Query\Term();
      $match->setTerm('twenty_four_hours', TRUE);
      $filter->addFilter($match);
    }

    if ($criteria->is_card_pay) {
        $match = new \Elastica\Query\Term();
      $match->setTerm('is_card_pay', TRUE);
      $filter->addFilter($match);
    }

    if ($criteria->have_ramp) {
        $match = new \Elastica\Query\Term();
      $match->setTerm('have_ramp', TRUE);
      $filter->addFilter($match);
    }

    if ($criteria->publish_date_from || $criteria->publish_date_to) {
      $match = new \Elastica\Query\Range();
      $value = array();
      if ($criteria->publish_date_from) {
        $value['gte'] = $criteria->publish_date_from;
      }

      if ($criteria->publish_date_to) {
        $value['lte'] = $criteria->publish_date_to;
      }
      $match->addField('date_publish', $value);
      $filter->addFilter($match);
    }

    if ($criteria->top_phone) {
        $match = new \Elastica\Query\Term();
        $match->setTerm('top_phone', $criteria->top_phone);
        $filter->addFilter($match);
    }

    $query_to_return = new \Elastica\Query\BoolQuery();

    $result_query = new \Elastica\Query();

    if ($criteria->geo_point) {
        if ($criteria->nearest) {
            $result_query->addSort([
                '_script' => [
                    'type' => 'number',
                    'script' => [
                        'lang' => 'painless',
                        'source' => '(doc[\'geo_point\'].arcDistance(' . (float)$criteria->geo_point->getLatitude() . ', ' . (float) $criteria->geo_point->getLongitude() . '))'
                    ],
                    "order" => "asc",
                ]
            ]);
        } else {
            $result_query->addSort([
                '_script' => [
                    'type' => 'number',
                    'script' => [
                        'lang' => 'painless',
                        'source' => '(doc[\'geo_point\'].arcDistance(' . (float)$criteria->geo_point->getLatitude() . ', ' . (float) $criteria->geo_point->getLongitude() . ')) <= 1000 ? 1 : 0'
                    ],
                    "order" => "desc",
                ]
            ]);
        }
    }

    // Если выполняется, то мы сортируем результаты группами
    // Изначально мы получаем результаты, которые соответствют геопоиску (данные результаты сортирутся по
    // баллам).
    // Затем мы получем все остальные результаты, которые между собой также сортируются по баллам
    // Для этого добавляем к запросу одно псевдополе, в котором указываем, соответсвует ли оно критериям
    // геопоиска
    if ($criteria->street_id) {
      $result_query->addSort([
        '_script' => [
          'type' => 'number',
          'script' => [
            'lang' => 'painless',
            'source' => '((doc[\'street\'].value == ' . (int) $criteria->street_id . ') ? 1 : 0)',
          ],
          "order" => "desc",
        ]
      ]);
    } elseif ($criteria->region_id) {
      $result_query->addSort([
        '_script' => [
          'type' => 'number',
          'script' => [
            'lang' => 'painless',
            'source' => '((doc[\'region\'].value == ' . (int) $criteria->region_id . ') ? 1 : 0)',
          ],
          "order" => "desc",
        ]
      ]);
    } elseif ($criteria->metro_station_id) {
      $result_query->addSort([
        '_script' => [
          'type' => 'number',
          'script' => [
            'lang' => 'painless',
            'source' => '((doc[\'metro_station_id\'].value == ' . (int) $criteria->metro_station_id . ') ? 1 : 0)',
          ],
          "order" => "desc",
        ]
      ]);

    } elseif ($criteria->district_id) {
      $result_query->addSort([
        '_script' => [
          'type' => 'number',
          'script' => [
            'lang' => 'painless',
            'source' => '((doc[\'district\'].value == ' . (int) $criteria->district_id . ') ? 1 : 0)'
          ],
          "order" => "desc",
        ]
      ]);
    }

    if ($query->getParams()) {
        $query_to_return->addMust($query);
    }

    if ($filter->getParams()) {
        $query_to_return->addFilter($filter);
    }

    $result_query->setQuery($query_to_return);

    if ($criteria->sort_by) {
      switch ($criteria->sort_by) {
        case 'geo_point':
            $result_query->addSort([
                '_script' => [
                    'type' => 'number',
                    'script' => [
                        'lang' => 'painless',
                        'source' => 'doc[\'geo_point\'].arcDistance(' . (float)$criteria->geo_point->getLatitude() . ', ' . (float) $criteria->geo_point->getLongitude() . ')'
                    ],
                    "order" => "desc",
                ]
            ]);
          break;
        case 'dt_edit':
          $result_query->addSort(array(
            'dt_edit' => array(
              'order' => 'desc',
            )
          ));
          break;
        case 'date_publish':
          $result_query->addSort(array(
            'date_publish' => array(
              'order' => 'desc',
            )
          ));
          break;
        case 'dt_publish':
          $result_query->addSort(array(
            'dt_publish' => array(
              'order' => 'desc',
            )
          ));
          break;
        case 'raw_clinics_in_start':
          //$search_params->addSortParam('clinic_status_id', array(ClinicStatusModel::RAW), 'DESC');
          break;
        case 'recomend':
          $result_query->addSort(array(
            'balls' => array(
              'order' => 'desc',
            )
          ));
          break;
        case 'rate':
            $result_query->addSort(array(
                'is_doctor' => array(
                    'order' => 'asc',
                )
            ));
            $result_query->addSort(array(
            'rate' => array(
              'order' => 'desc',
            )
          ));
          break;
      }
    }

    $this->addPaging($criteria, $result_query);

    return $result_query;
  }

  public function getListByGeoPointAndDistance(GeoPoint $geo_point, $distance)
  {
    /**
     * @var ClinicSearchParams $criteria
     */
    $filter = new \Elastica\Query\BoolQuery();

    $match = new \Elastica\Query\Term();
    $match->setTerm('is_active', TRUE);
    $filter->addFilter($match);

    $location = array(
      'lat' => $geo_point->getLatitude(),
      'lon' => $geo_point->getLongitude()
    );
    $distance = (int)$distance . 'm';
    $match = new \Elastica\Query\GeoDistance('geo_point', $location, $distance);
    $filter->addFilter($match);

    $result_query = new \Elastica\Query();
    $result_query->setQuery($filter);
    $result_query->setSize(1000);

    try {
      $data = $this->getType()->search($result_query);
    }catch(Exception $e) {
      $data = [];
    }
    $result = array();
    foreach ($data as $v) {
      /**
       * @var \Elastica\Result $v
       */
      $result[] = $v->getData();
    }


    return $result;
  }

  public function getActiveSpecializationsByCity($cityId)
  {
      $filter = new \Elastica\Query\BoolQuery();

      $match = new \Elastica\Query\Term();
      $match->setTerm('city', (int)$cityId);
      $filter->addFilter($match);

      $match = new \Elastica\Query\Term();
      $match->setTerm('is_active', true);
      $filter->addFilter($match);

      $match = new \Elastica\Query\Term();
      $match->setTerm('is_not_example', true);
      $filter->addFilter($match);

      $agg = new \Elastica\Aggregation\Terms('specializations');
      $agg->setField('specializations');
      $agg->setSize(1000);

      $q = new \Elastica\Query();
      $q->setQuery($filter);
      $q->addAggregation($agg);
      $q->setSize(0);

      $data = $this->getType()->search($q);
      $aggregation = $data->getAggregation('specializations');
      $result = [];
      if (!empty($aggregation['buckets'])) {
          foreach ($aggregation['buckets'] as $bucket) {
              $result[$bucket['key']] = [
                  'id' => $bucket['key'],
                  'count' => $bucket['doc_count']
              ];
          }
      }
      return $result;
  }

}
