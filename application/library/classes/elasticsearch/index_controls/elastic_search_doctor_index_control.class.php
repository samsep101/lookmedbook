<?php

class ElasticSearchDoctorIndexControl extends ElasticSearchModelIndexControl
{

  public function __construct($indexName = null)
  {
    parent::__construct($indexName);
    $this->object_factory = new DoctorElasticSearchObjectFactory();
  }

  /**
   * @return \Elastica\Type
   */
  protected function getType()
  {
    return $this->getIndex()->getType('doctor');
  }


  protected function buildQueryObject(ModelSearchCriteria $criteria)
  {
    /**
     * @var DoctorSearchParams $criteria
     */
    $filter = new \Elastica\Query\BoolQuery();

    $query = new Elastica\Query\Match();

    $result_query = new \Elastica\Query();
    $result_query->setStoredFields(['id', 'is_equal_to_geo']);

    if ($criteria->doctor_name) {
      $query->setFieldQuery('full_name', $criteria->doctor_name);
      $query->setFieldOperator('full_name', 'AND');
      $criteria->specialty_id = null;
    }
    else {
      if ($criteria->not_virtual) {
        $match = new \Elastica\Query\Term();
        $match->setTerm('is_virtual', false);
        $filter->addFilter($match);
      }

      if ($criteria->specialty_id) {
        $specialties = array();

        $specialties[] = $criteria->specialty_id;

        if ($criteria->suitable_specialties_ids) {
          foreach ($criteria->suitable_specialties_ids as $specialty_id)
            $specialties[] = $specialty_id;
        }

        $match = new \Elastica\Query\Terms();
        $match->setTerms('specialty_ids', $specialties);
        $filter->addFilter($match);
      }

      if ($criteria->purpose_of_visit_id) {
        $match = new \Elastica\Query\Term();
        $match->setTerm('purposes_of_visit', $criteria->purpose_of_visit_id);
        $filter->addFilter($match);
      }

      if ($criteria->visit_type == 'home') {
        $match = new \Elastica\Query\Term();
        $match->setTerm('is_leave_the_house', true);
        $filter->addFilter($match);
      }

      if ($criteria->doctor_sex_id) {
        $match = new \Elastica\Query\Term();
        $match->setTerm('sex', $criteria->doctor_sex_id);
        $filter->addFilter($match);
      }

      if ($criteria->morning_time) {
        $match = new \Elastica\Query\Term();
        $match->setTerm('is_has_morning_time', (bool)$criteria->morning_time);
        $filter->addFilter($match);
      }

      if ($criteria->evening_time) {
        $match = new \Elastica\Query\Term();
        $match->setTerm('is_has_evening_time', (bool)$criteria->evening_time);
        $filter->addFilter($match);
      }

      if ($criteria->weekend_time) {
        $match = new \Elastica\Query\Term();
        $match->setTerm('is_has_weekend_time', (bool)$criteria->weekend_time);
        $filter->addFilter($match);
      }

      if ($criteria->doctor_type == 'adult') {
        $match = new \Elastica\Query\Term();
        $match->setTerm('is_adult', true);
        $filter->addFilter($match);
      }

      if ($criteria->doctor_type == 'children') {
        $match = new \Elastica\Query\Term();
        $match->setTerm('is_children', true);
        $filter->addFilter($match);
      }

      if ($criteria->doctor_type == 'pregnant') {
        $match = new \Elastica\Query\Term();
        $match->setTerm('is_pregnant', true);
        $filter->addFilter($match);
      }

      if ($criteria->has_avatar) {
        $match = new \Elastica\Query\Term();
        $match->setTerm('is_has_avatar', true);
        $filter->addFilter($match);
      }
    }

    if ($criteria->registry_user_id) {
      /**
       * @var UserManager $user_manager
       * @var UserModel $user
       */
      $user_manager = ModelManagerFactory::getByName('user');
      $user = $user_manager->getOneById($criteria->registry_user_id);

      $roles = array(RoleModel::FREELANCE_MANAGER, RoleModel::ACCOUNT_MANAGER);
      if (in_array($user->role_id, $roles)) {
        $match = new \Elastica\Query\Term();
        $match->setTerm('registry_users', $criteria->registry_user_id);
        $filter->addFilter($match);
      }
    }

    if ($criteria->is_active) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('is_active', true);
      $filter->addFilter($match);
    }

    if ($criteria->city_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('cities', $criteria->city_id);
      $filter->addFilter($match);
    }

    if (!empty($criteria->clinic_id)) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('clinics.id', $criteria->clinic_id);
      $filter->addFilter($match);
    }

    if ($criteria->district_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('clinics.district', $criteria->district_id);
      $filter->addFilter($match);
    }

    if ($criteria->metro_station_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('clinics.metro_station_ids.id', $criteria->metro_station_id);
      $filter->addFilter($match);
    }

    if ($criteria->region_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('clinics.region', $criteria->region_id);
      $filter->addFilter($match);
    }

    if ($criteria->street_id) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('clinics.street', $criteria->street_id);
      $filter->addFilter($match);
    }

    if (!empty($criteria->discount) && $criteria->discount==1) {
      $range = new \Elastica\Query\Range();
      $range->addField('date_from',
                    array(  'from' => '1970-01-01',
                            'to' => date('Y-m-d')
                         )
                 );
/*
      $range->addField('date_to',
                    array(  'from' => date('Y-m-d'),
                            'to' => '2100-01-01'
                         )
                 );

*/
      $filter->addFilter($range);
    }
    //die(print_r($filter_and));

    if (!empty($criteria->_id)) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('_id', $criteria->_id);
      $filter->addFilter($match);
    }

    if ($criteria->street_id && !$criteria->region_id) {
      $region_manager = ModelManagerFactory::getByName('street');
      $street = $region_manager->getOneById($criteria->street_id);
      $region_id = $street->regions[0]->getId();
      $match = new \Elastica\Query\Term();
      $match->setTerm('clinics.region', $region_id);
      $filter->addFilter($match);
    }

    if($criteria->geo_point && !$criteria->nearest) {
      $point = array(
        'lat' => $criteria->geo_point->getLatitude(),
        'lon' => $criteria->geo_point->getLongitude()
      );
      //$match = new \Elastica\Filter\GeoDistance('clinics.geo_point', $point, ($criteria->distance / 1000) . 'km');

      $distance = $criteria->distance ? (string)(int) $criteria->distance : '1000';
      $match = new \Elastica\Query\GeoDistance('clinics.geo_point', $point, $distance . 'm');
      $filter->addFilter($match);
    }

    if ($criteria->is_has_clinic !== null) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('is_has_clinic', $criteria->is_has_clinic);
      $filter->addFilter($match);
    }

    if ($criteria->is_has_active_clinic) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('is_has_active_clinic', true);
      $filter->addFilter($match);
    }

    if ($criteria->has_visit_slots) {
      $match = new \Elastica\Query\Term();
      $match->setTerm('is_has_visit_slots', true);
      $filter->addFilter($match);
    }

    $complex_query = new \Elastica\Query\BoolQuery();

    if ($query->getParams()) {
        $complex_query->addMust($query);
    }

    if ($criteria->high_priority_doctor_ids) {
      $match = new \Elastica\Query\Terms();
      $match->setTerms('id', $criteria->high_priority_doctor_ids);
      $complex_query->addShould($match);
    }

    if ($criteria->high_priority_clinic_ids) {
      $match = new \Elastica\Query\Terms();
      $match->setTerms('clinics.id', $criteria->high_priority_clinic_ids);
      $complex_query->addShould($match);
    }

    // Если выполняется, то мы сортируем результаты группами
    // Изначально мы получаем результаты, которые соответствют геопоиску (данные результаты сортирутся по
    // баллам).
    // Затем мы получем все остальные результаты, которые между собой также сортируются по баллам
    // Для этого добавляем к запросу одно псевдополе, в котором указываем, соответсвует ли оно критериям
    // геопоиска

    if ($criteria->street_id) {
      //$result_query->addScriptField('is_equal_to_geo', new \Elastica\Script('((doc[\'clinics.street\'].value == '.$criteria->street_id.') ? 1 : 0)'));

      $result_query->addSort([
        '_script' => [
          'type' => 'number',
          'script' => [
            'lang' => 'painless',
            'source' => '((doc[\'clinics.street\'].value == ' . (int) $criteria->street_id . ') ? 1 : 0)',
          ],
          "order" => "desc",
        ]
      ]);
    } elseif ($criteria->region_id) {
      //$result_query->addScriptField('is_equal_to_geo', new \Elastica\Script('((doc[\'clinics.region\'].value == '.$criteria->region_id.') ? 1 : 0)'));

      $result_query->addSort([
        '_script' => [
          'type' => 'number',
          'script' => [
            'lang' => 'painless',
            'source' => '((doc[\'clinics.region\'].value == ' . (int) $criteria->region_id . ') ? 1 : 0)',
          ],
          "order" => "desc",
        ]
      ]);

    } elseif ($criteria->district_id) {
      //$result_query->addScriptField('is_equal_to_geo', new \Elastica\Script('((doc[\'clinics.district\'].value == '.$criteria->district_id.') ? 1 : 0)'));

      $result_query->addSort([
        '_script' => [
          'type' => 'number',
          'script' => [
            'lang' => 'painless',
            'source' => '((doc[\'clinics.district\'].value == ' . (int) $criteria->district_id . ') ? 1 : 0)'
          ],
          "order" => "desc",
        ]
      ]);
    }

    if ($criteria->geo_point) {
        if ($criteria->nearest) {
            $result_query->addSort([
                '_script' => [
                    'type' => 'number',
                    'script' => [
                        'lang' => 'painless',
                        'source' => '(doc[\'clinics.geo_point\'].arcDistance(' . (float)$criteria->geo_point->getLatitude() . ', ' . (float) $criteria->geo_point->getLongitude() . '))'
                    ],
                    "order" => "asc",
                ]
            ]);
        } else {
            $distance = ($criteria->distance ? (int) $criteria->distance : 1000);
            $result_query->addSort([
                '_script' => [
                    'type' => 'number',
                    'script' => [
                        'lang' => 'painless',
                        'source' => '(doc[\'clinics.geo_point\'].arcDistance(' . (float)$criteria->geo_point->getLatitude() . ', ' . (float) $criteria->geo_point->getLongitude() . ')) <= ' . $distance . ' ? 1 : 0'
                    ],
                    "order" => "desc",
                ]
            ]);
        }
    }

    if ($filter->getParams()) {
      $complex_query->addFilter($filter);
    }

    $result_query->setQuery($complex_query);

    /*if ($criteria->doctor_name) {
      $result_query->addSort([
        '_score' => [
          'order' => 'desc'
        ]
      ]);
    }*/

    switch ($criteria->sort_by) {
      case 'balls':
      case 'rate':
        $result_query->addSort(array(
          'rate' => array(
            'order' => 'desc',
          )
        ));
        $result_query->addSort(array(
          'reviews_count' => array(
            'order' => 'desc',
          )
        ));
        $result_query->addSort(array(
          'is_has_avatar' => array(
            'order' => 'desc',
          )
        ));
        break;
      case 'rand':
        $result_query->addSort(array(
          'id' => array(
            'order' => 'RAND'
          )
        ));
        break;
    }

      if ($criteria->sort_salt) {
          // сортировка по псевдополю для "перетасовывания" врачей в листинге
          $result_query->addSort([
              '_script' => [
                  'type'   => 'string',
                  'script' => [
                      'lang'   => 'painless',
                      'source' => "(doc['_uid'] + params.salt).hashCode()",
                      'params' => [
                          'salt' => (string)(int)$criteria->sort_salt
                      ]
                  ]
              ]
          ]);
      }

    if ($criteria->page && $criteria->by_page) {
      $size = $criteria->by_page;
      if ($criteria->get_extra_item) {
        $result_query->setSize($size + 1);
      } else {
        $result_query->setSize($size);
      }

      $from = ($criteria->page - 1) * $criteria->by_page;
      $result_query->setFrom($from);
    } else {
      $result_query->setSize(10000);
      $result_query->setFrom(0);
    }
    return $result_query;
  }
}
