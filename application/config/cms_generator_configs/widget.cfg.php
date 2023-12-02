<?php
    $widget = array(
        'table'     => DB_PREFIX . 'widget',
        'title'     => 'Виджеты',
        'fields'    => array(
            'id'       => 'index',
            'name'    => 'input',
            'code' => array(
                'type' => 'text',
                'style' => 'width: 500px; height: 300px'
            ),
            'element_id' => 'input',
            'resource_url' => 'just_text',
            'identifier' => 'just_text',
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
            'is_need_to_compile' => 'checkbox',
            'size' => 'input',
            'counters_code' => array(
                'type' => 'text',
                'style' => 'width: 500px; height: 300px;'
            ),
            'accept_html' => array(
                'type' => 'text',
                'style' => 'width: 500px; height: 300px;'
            ),
            'banner_html' => array(
                'type' => 'text',
                'style' => 'width: 500px; height: 300px;'
            ),
            'banner_240_160_html' => array(
                'type' => 'text',
                'style' => 'width: 500px; height: 300px;'
            ),
            'form_html' => array(
                'type' => 'text',
                'style' => 'width: 500px; height: 300px;'
            ),
            'notification_html' => array(
                'type' => 'text',
                'style' => 'width: 500px; height: 300px;'
            ),
            'yandex_metrics_id' => 'input',
            'google_analytics_id' => 'input',
            'widget_id' => array(
                'type' => 'category',
                'cross_name' => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'widget',
                'sort_by' => 'name'
            ),
            'folder' => 'input'
        ),
        'generator' => array(
            'fields'   => array(
                'id'       => 'ID',
                'name' => 'Название',
                'code' => 'Код',
                'element_id' => 'Идентификатор элемента, куда вставляется виджет',
                'city_id' => 'Город',
                'specialty_id' => 'Специализация',
                'require_sms' => 'Требует подтверждения по sms',
                'resource_url' => 'Адрес каталога виджета',
                'is_need_to_compile' => 'Скомпилировать',
                'size' => 'Размер',
                'counters_code' => 'Код счетчиков',
                'accept_html' => 'accept.html',
                'banner_html' => 'banner.html',
                'banner_240_160_html' => 'banner240_160.html',
                'form_html' => 'form.html',
                'notification_html' => 'notification.html',
                'yandex_metrics_id' => 'Идентификатор Яндекс.Метрика',
                'google_analytics_id' => 'Идентификатор Гугл.Аналитикс',
                'folder' => 'Название каталога',
                'widget_id' => 'Исходный виджет',
            ),
            'list'     => array(
                'fields'  => array('id', 'name'),
                'title'   => 'Список типов виджетов',
            ),
            'edit'     => array(
                'fields' => array(
                    'Основные данные' => array(
                        'resource_url',
                        'name',
                        'element_id',
                        'city_id',
                        'specialty_id',
                        'require_sms',
                        'size',
                        'yandex_metrics_id',
                        'google_analytics_id',
                    ),
                    'HTML файлы' => array(
                        'accept_html',
                        'banner_html',
                        'banner_240_160_html',
                        'form_html',
                        'notification_html',
                        'is_need_to_compile',
                    )
                ),
                'title'  => 'Редактирование виджета',
                'submit' => 'Сохранить',
            ),
            'add'      => array(
                'fields' => array(
                    'Основные данные' => array(
                        'widget_id',
                        'name',
                        'folder',
                        'name',
                        'element_id',
                        'city_id',
                        'specialty_id',
                        'require_sms',
                        'size',
                        'yandex_metrics_id',
                        'google_analytics_id',
                    ),
                ),
                'title'  => 'Добавление виджета',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('widget', $widget);