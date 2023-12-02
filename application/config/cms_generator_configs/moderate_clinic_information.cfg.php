<?php

    $moderate_clinic_information = array(
        'table'     => DB_PREFIX . 'moderate_clinic_information',
        'title'     => 'Модерируемая информация: информация о клинике',
        'fields'    => array(
            'id'                      => 'index',
            'clinic_id'               => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'clinic',
                'first'       => array('0' => '',),
                'filter'      => 'true',
                'sort_by'     => 'name'
            ),
            'name'                    => 'input',
            'full_name'               => 'input',
            'clinic_type_id'          => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'clinic_type',
                'first'       => array('0' => '',),
                'filter'      => 'true',
                'sort_by'     => 'name'
            ),
            'is_children'             => array(
                'type'  => 'checkbox',
                'label' => 'Да/Нет'
            ),
            'is_pregnant'             => array(
                'type'  => 'checkbox',
                'label' => 'Да/Нет'
            ),
            'is_handicapped'          => array(
                'type'  => 'checkbox',
                'label' => 'Да/Нет'
            ),
            'is_card_pay'             => array(
                'type'  => 'checkbox',
                'label' => 'Да/Нет'
            ),
            'is_state'                => array(
                'type'  => 'checkbox',
                'label' => 'Да/Нет'
            ),
            'is_prescribe_sick_leave' => array(
                'type'  => 'checkbox',
                'label' => 'Да/Нет'
            ),
            'is_yandex_send'          => array(
                'type'  => 'checkbox',
                'label' => 'Да/Нет'
            ),
            'only_children'           => array(
                'type'  => 'checkbox',
                'label' => 'Да/Нет'
            ),
            'only_adult'              => array(
                'type'  => 'checkbox',
                'label' => 'Да/Нет'
            ),
            'city_id'                 => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'city',
                'first'       => array('0' => '',),
                'filter'      => 'true',
                'sort_by'     => 'name'
            ),
            'address'                 => 'input',
            'metro_station_id'        => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'metro_station',
                'first'       => array('0' => '',),
                'filter'      => 'true',
                'sort_by'     => 'name'
            ),
            'director_fio'            => 'input',
            'contract_number'         => 'input',
            'date_contract'           => array(
                'type'   => 'date',
                'format' => 'd-m-Y'
            ),
            'legal_entity'            => 'input',
            'moderate_status_id'      => array(
                'type'   => 'listvalue',
                'values' => array(
                    '1' => 'Редактируется клиникой',
                    '2' => 'Нужна проверка',
                    '3' => 'Отправлено в клинику на доработку',
                    '4' => 'Опубликован на '.SITE_NAME,
                ),
                'filter' => 'true'
            ),
            'revision_number'         => 'input',
        ),
        'generator' => array(
            'fields' => array(
                'id'                      => 'ID',
                'clinic_id'               => 'Клиника',
                'name'                    => 'Название',
                'full_name'               => 'Полное наименование',
                'clinic_type_id'          => 'Тип клиники',
                'is_children'             => 'Для детей',
                'is_pregnant'             => 'Для беременных',
                'is_handicapped'          => 'Для инвалидов',
                'is_card_pay'             => 'Оплата карточкой',
                'is_cach_pay'             => 'Оплата наличными',
                'is_state'                => 'Государственная клиника',
                'is_prescribe_sick_leave' => 'Выписывает больничные листы',
                'is_yandex_send'          => 'Публиковать на Яндексе',
                'only_children'           => 'Только для детей',
                'only_adult'              => 'Только для взрослых',
                'city_id'                 => 'Город',
                'address'                 => 'Адрес',
                'metro_station_id'        => 'Станция метро',
                'director_fio'            => 'ФИО генерального директора',
                'moderate_status_id'      => 'Статус модерации',
                'revision_number'         => 'Ревизия',
                'contract_number'         => 'Номер договора',
                'date_contract'           => 'Дата договора',
                'legal_entity'            => 'Юр. лицо клиники',
            ),
            'list'   => array(
                'fields'  => array(
                    'clinic_id',
                    'name',
                ),
                'title'   => 'Список',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'clinic_id',
                        'name',
                        'full_name',
                        'clinic_type_id',
                        'is_children',
                        'is_pregnant',
                        'is_handicapped',
                        'is_card_pay',
                        'is_cache_pay',
                        'is_state',
                        'is_prescribe_sick_leave',
                        'is_yandex_send',
                        'only_children',
                        'only_adult',
                        'city_id',
                        'address',
                        'metro_station_id',
                        'director_fio',
                        'date_contract',
                        'legal_entity',
                        'contract_number',
                        'moderate_status_id',
                        'revision_number',
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'clinic_id',
                        'name',
                        'full_name',
                        'clinic_type_id',
                        'is_children',
                        'is_pregnant',
                        'is_handicapped',
                        'is_card_pay',
                        'is_cach_pay',
                        'is_state',
                        'is_prescribe_sick_leave',
                        'is_yandex_send',
                        'only_children',
                        'only_adult',
                        'city_id',
                        'address',
                        'metro_station_id',
                        'director_fio',
                        'date_contract',
                        'legal_entity',
                        'contract_number',
                        'moderate_status_id',
                        'revision_number',
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );
    CmsGeneratorConfigRegister::add('moderate_clinic_information', $moderate_clinic_information);
