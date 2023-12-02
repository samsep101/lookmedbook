<?php

    $widget_site = array(
        'table'     => DB_PREFIX . 'widget_site',
        'title'     => 'Зарегистрированные виджеты',
        'fields'    => array(
            'id'       => 'index',
            'widget_id'    => array(
                'type' => 'category',
                'cross_name' => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'widget',
            ),
            'host' => 'input',
            'name' => 'input',
            'is_active' => 'checkbox',
            'element_id' => 'input',
            'city_id' => array(
                'type' => 'category',
                'cross_name' => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'city',
                'first' => array(
                    '0' => '',
                ),
                'search_params' => array(
                    'service_flag' => array(
                        'field_name' => 'service_flag',
                        'value' => 1
                    ),
                ),
                'sort_by' => 'name'
            ),
            'specialty_id' => array(
                'type' => 'category',
                'cross_name' => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'specialty',
                'first' => array(
                    '0' => '',
                ),
                'sort_by' => 'name'
            ),
            'require_sms' => 'checkbox',
            'settings_url' => 'just_text',
            'applicable_element_id' => 'just_text',
            'applicable_city.name' => 'just_text',
            'applicable_specialty.name' => 'just_text',
            'applicable_require_sms' => 'just_text',
            'applicable_size' => 'just_text',
            'counters_code' => array(
                'type' => 'text',
                'style' => 'width: 500px;',
            ),
            'widget_code' => 'just_text',
            'size' => 'input',
            'yandex_metrics_id' => 'input',
            'google_analytics_id' => 'input',
			'example_link' => 'just_text'
        ),
        'generator' => array(
            'fields'   => array(
                'id'       => 'ID',
                'widget_id' => 'Тип виджета',
                'host' => 'URL сайта',
                'name' => 'Название',
                'is_active' => 'Активен',
                'element_id' => 'Идентификатор элемента',
                'city_id' => 'Город',
                'specialty_id' => 'Специализация',
                'require_sms' => 'Необходимо подтверждение sms',
                'settings_url' => 'URL файла с настройками',
                'applicable_element_id' => 'Идентификатор элемента',
                'applicable_city.name' => 'Город',
                'applicable_specialty.name' => 'Специализация',
                'applicable_require_sms' => 'Необходимо подтверждение sms',
                'counters_code' => 'Код счетчика аналитики',
                'widget_code' => 'Код',
                'size' => 'Размер',
                'yandex_metrics_id' => 'Идентификатор Яндекс.Метрика',
                'google_analytics' => 'Идентификатор Гугл.Аналитикс',
                'example_link' => 'Пример',
            ),
            'list'     => array(
                'fields'  => array(
                    'id',
                    'widget_id',
                    'name',
                    'host',
                    'is_active',
                    'settings_url',
                ),
                'title'   => 'Список виджетов',
            ),
            'edit'     => array(
                'fields' => array(
                    'Основные данные' => array(
                        'settings_url',
                        'widget_id',
                        'name',
                        'host',
                        'is_active'
                    ),
                    'Настройки' => array(
                        'element_id',
                        'specialty_id',
                        'city_id',
                        'require_sms',
                        'size',
                        'yandex_metrics_id',
                        'google_analytics_id'
                    ),
                    'Применяемые настройки' => array(
                        'applicable_element_id',
                        'applicable_city.name',
                        'applicable_specialty.name',
                        'applicable_require_sms',
                        'applicable_size',
                    ),
                    'Код для вставки виджета' => array(
                        'widget_code',
						'example_link'
                    )
                ),
                'title'  => 'Редактирование виджета',
                'submit' => 'Сохранить',
            ),
            'add'      => array(
                'fields' => array(
                    'Основные данные' => array(
                        'widget_id',
                        'host',
                        'name',
                        'is_active',
                    ),
                    'Настройки' => array(
                        'element_id',
                        'specialty_id',
                        'city_id',
                        'require_sms',
                        'yandex_metrics_id',
                        'google_analytics_id'
                    )
                ),
                'title'  => 'Добавление виджета',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('widget_site', $widget_site);