<?php

    $moderate_clinic_information = array(
        'table'  => DB_PREFIX . 'moderate_clinic_information',
        'title'  => 'О клинике',
        'fields' => array(
            'id'                      => 'index',
            'name'                    => array(
                'type'  => 'input',
                'style' => 'width: 300px;',
            ),
            'full_name'               => array(
                'type'  => 'input',
                'style' => 'width: 630px;',
            ),
            'is_active'               => array(
                'type'  => 'styled_checkbox',
                'label' => 'Клиника опубликована на '.SITE_NAME,
            ),
            'not_work'                => array(
                'type'  => 'styled_checkbox',
                'label' => 'Не работаем с клиникой'
            ),
            'redirect_list'           => array(
                'type'  => 'styled_checkbox',
                'label' => 'Добавить клинику в список отображаемх страниц при отсутствии страницы'
            ),
            'top_phone'           => array(
                'type'  => 'input',
                'style' => 'width: 250px;',
            ),
            'is_contract'             => array(
                'type'  => 'styled_checkbox',
                'label' => 'Есть договор'
            ),
            'clinic_type_id'          => array(
                'type'              => 'list_checkbox',
                'main_table'        => 'clinic',
                'result_table_name' => 'clinic_type',
                'cross_table_name'  => 'clinic_to_types',
                'result_field_name' => 'clinic_id',
                'cross_field_name'  => 'clinic_type_id',
            ),
            'clinic_service_id'       => array(
                'type'              => 'list_checkbox',
                'main_table'        => 'clinic',
                'result_table_name' => 'clinic_services',
                'cross_table_name'  => 'clinic_to_services',
                'result_field_name' => 'clinic_id',
                'cross_field_name'  => 'clinic_service_id',
                'style_class'       => 'clinicInfServices',
            ),
            'is_children'             => array(
                'type'  => 'styled_checkbox',
                'label' => 'Для детей'
            ),
            'is_adult'                => array(
                'type'  => 'styled_checkbox',
                'label' => 'Для взрослых'
            ),
            'is_pregnant'             => array(
                'type'  => 'styled_checkbox',
                'label' => 'Для беременных'
            ),
            'is_handicapped'          => array(
                'type'  => 'styled_checkbox',
                'label' => 'Для инвалидов'
            ),
            'is_card_pay'             => array(
                'type'  => 'styled_checkbox',
                'label' => 'Банковской карточкой'
            ),
            'is_cache_pay'            => array(
                'type'  => 'styled_checkbox',
                'label' => 'Наличные'
            ),
            'is_state'                => array(
                'type'  => 'styled_checkbox',
                'label' => 'Государственная клиника'
            ),
            'is_prescribe_sick_leave' => array(
                'type'  => 'styled_checkbox',
                'label' => 'Выписывает больничные листы'
            ),
            'is_yandex_send'          => array(
                'type'  => 'styled_checkbox',
                'label' => 'Публиковать на Яндексе'
            ),
            'only_children'           => array(
                'type'  => 'styled_checkbox',
                'label' => 'Только для детей'
            ),
            'only_adult'              => array(
                'type'  => 'styled_checkbox',
                'label' => 'Только для взрослых'
            ),
            'city_id'                 => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'city',
                'first'       => array(
                    '' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
                'style'       => '',
            ),
            'address'                 => array(
                'type'        => 'input',
                'style'       => 'width: 430px;',
                'placeholder' => 'Улица, дом'
            ),
            'postcode'                => array(
                'type'  => 'input',
                'style' => 'width: 100px;',
            ),
            'metro_station_id'        => array(
                'type'        => 'category',
                'cross_name'  => 'name_with_city_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'metro_station',
                'first'       => array(
                    '' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'director_fio'            => array(
                'type'  => 'input',
                'style' => 'width: 630px;'
            ),
            'contract_number'         => array(
                'type'  => 'input',
                'style' => 'width: 630px;'
            ),
            'date_contract'           => array(
                'type'   => 'date',
                'format' => 'd-m-Y',
                'style'  => 'width: 200px'
            ),
            'legal_entity'            => array(
                'type'  => 'input',
                'style' => 'width: 630px;'
            ),
            'site'                    => array(
                'type'  => 'input',
                'style' => 'width: 250px;'
            ),
            'latitude'                => array(
                'type'        => 'input',
                'style'       => 'width: 232px;',
                'placeholder' => '12.1212'
            ),
            'longitude'               => array(
                'type'        => 'input',
                'style'       => 'width: 232px;',
                'placeholder' => '12.1212'
            ),
            'rate'                    => array(
                'type'  => 'input',
                'style' => 'width: 50px'
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('moderate_clinic_information', $moderate_clinic_information);