<?php

    class ClinicElasticSearchMapping implements IElasticSearchMapping
    {
        /**
         * Получить меппинг для данной сущности
         *
         * @link http://www.elasticsearch.org/guide/reference/mapping/
         * @return array
         */
        public function getFieldsMapping()
        {
            return array(
                'id' => array(
                    'type'           => 'integer',
                ),
                'specialties' => array(
                    'properties' => array(
                        'id' => array(
                            'type'           => 'integer',
                        ),
                        'purposes_of_visit' => array(
                            'type'           => 'integer',
                        )
                    ),
                ),
                'services' => array(
                    'properties' => array(
                        'id' => array(
                            'type'           => 'integer',
                        )
                    ),
                ),
                'service_categories' => array(
                    'properties' => array(
                        'id' => array(
                            'type'           => 'integer',
                        )
                    ),
                ),
                'types' => array(
                    'properties' => array(
                        'id' => array(
                            'type'           => 'integer',
                        )
                    ),
                ),
                'address' => array(
                    'type'           => 'text',
                    'index'          => true,
                ),
                'specializations' => array(
                    'type'           => 'integer',
                ),
                'primary_clinic_id' => array(
                    'type'           => 'integer',
                ),
                'is_children' => array(
                    'type'           => 'boolean',
                ),
                'is_handicapped' => array(
                    'type'           => 'boolean',
                ),
                'is_pregnant' => array(
                    'type'           => 'boolean',
                ),
                'is_day_and_night' => array(
                    'type'           => 'boolean',
                ),
                'name' => array(
                    'type'           => 'text',
                    'index'          => true,
                    'analyzer'       => 'autocomplete',
                    'search_analyzer' => 'searchAnalyzer',
                    'boost' => 2,
                ),
                'doctors' => array(
                    'type'           => 'integer',
                ),
                'city' => array(
                    'type'           => 'integer',
                ),
                'is_active' => array(
                    'type'           => 'boolean',
                ),
                'is_not_example' => array(
                    'type'           => 'boolean',
                ),
                'geo_point' => array(
                    'type'           => 'geo_point',
                ),
                'registry_users' => array(
                    'type'           => 'integer',
                ),
                'freelancers' => array(
                    'type'           => 'integer',
                ),
                'is_region' => array(
                    'type'           => 'boolean',
                ),
                'region' => array(
                    'type'           => 'integer',
                ),
                'status' => array(
                    'type'           => 'integer',
                ),
                'street' => array(
                    'type'           => 'integer',
                ),
                'district' => array(
                    'type'           => 'integer',
                ),
                'balls' => array(
                    'type'           => 'float',
                ),
                'date_publish' => array(
                    'type'           => 'date',
                ),
                'dt_publish' => array(
                    'type'           => 'long',
                ),
                'rate' => array(
                  'type' => 'scaled_float',
                  'scaling_factor' => 100,
                ),
                'only_children' => array(
                    'type'           => 'boolean',
                ),
                'is_card_pay' => array(
                    'type'           => 'boolean',
                ),
                'twenty_four_hours' => array(
                    'type'           => 'boolean',
                ),
                'metro_station_ids' => array(
                    'properties' => array(
                        'id' => array(
                            'type'           => 'integer',
                        )
                    ),
                ),
                'have_ramp' => array(
                    'type'           => 'boolean',
                ),
                'is_doctor' => array(
                    'type'           => 'boolean',
                ),
                'top_phone' => array(
                    'type'           => 'keyword',
                ),
            );
        }
    }
