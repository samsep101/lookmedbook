<?php

	/**
	 * Задает меппинг для данных
	 *
	 * @link http://www.elasticsearch.org/guide/reference/mapping/
	 */
	class DoctorElasticSearchMapping implements IElasticSearchMapping
	{
		/**
		 * Получить меппинг для данной сущности
		 *
		 * @return array
		 */
		public function getFieldsMapping()
		{
			return array(
				'id' => array(
					'type' => 'integer',
				),
				'specialty_ids' => array(
					'type' => 'integer',
				),
				'cities' => array(
					'type' => 'integer',
				),
				'purposes_of_visit' => array(
					'type' => 'integer',
				),
				'is_leave_the_house' => array(
					'type' => 'boolean',
				),
				'is_children' => array(
					'type' => 'boolean',
				),
				'is_has_morning_time' => array(
					'type' => 'boolean',
				),
				'is_has_evening_time' => array(
					'type' => 'boolean',
				),
				'is_has_weekend_time' => array(
					'type' => 'boolean',
				),
				'is_has_any_time' => array(
					'type' => 'boolean',
				),
				'full_name' => array(
					'type' => 'text',
					'analyzer' => 'autocomplete',
          'search_analyzer' => 'searchAnalyzer',
          'index' => true,
					'store' => true,
				),
				'sex' => array(
					'type' => 'integer',
				),
				'doctor_type' => array(
					'type' => 'integer',
				),
				'actions' =>array(
					'properties' => array(
						'id' => array(
							'type' => 'integer',
                                                 ),
						'clinic_id' => array(
							'type' => 'integer',
                                                 ),
						'date_from' => array(
							'type' => 'date',
                                                        'format'=>'yyyy-MM-dd',
                                                 ),
						'date_to' => array(
							'type' => 'date',
                                                        'format'=>'yyyy-MM-dd',
                                                 )
                                        )
                                    
                                ),
                                'clinics' => array(
					'properties' => array(
						'id' => array(
							'type' => 'integer',
						),
						'geo_point' => array(
							'type' => 'geo_point',
						),
						'district' => array(
							'type' => 'integer',
						),
						'region' => array(
							'type' => 'integer',
						),
						'street' => array(
							'type' => 'integer',
						),
                        'metro_station_ids' => array(
                            'properties' => array(
                                'id' => array(
                                    'type'           => 'integer',
                                )
                            ),
                        ),
						'specialties' => array(
							'type' => 'integer',
						),
					),
				),
				'is_has_visit_slots' => array(
					'type' => 'boolean',
				),
				'is_active' => array(
					'type' => 'boolean',
				),
				'is_has_avatar' => array(
					'type' => 'boolean',
				),
				'is_virtual' => array(
					'type' => 'boolean',
				),
				'is_unbounded' => array(
					'type' => 'boolean',
				),
				'is_adult' => array(
					'type' => 'boolean',
				),
				'is_pregnant' => array(
					'type' => 'boolean',
				),
				'balls' => array(
					'type' => 'float',
				),
				'rate' => array(
					'type' => 'scaled_float',
					'scaling_factor' => 100,
				),
				'is_has_clinic' => array(
					'type' => 'boolean',
				),
                'is_has_active_clinic' => array(
                    'type' => 'boolean',
                ),
				'registry_users' => array(
					'type' => 'integer'
				),
                'reviews_count' => array(
                    'type' => 'integer',
                ),
				'_boost' => array(
					'type' => 'float',
				),
			);
		}

	}