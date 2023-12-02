<?php
    $cms_notification_settings = array(
        'table'     => DB_PREFIX . 'notification_settings',
        'title'     => 'Настройка уведомлений',
        'fields'    => array(
            'id'                  => 'index',
            'account_id'          => array(
                'type'        => 'category',
                'cross_name'  => 'full_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'account',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'full_name',
            ),

            'sms_notify_phone_id' => array(
                'type'        => 'category',
                'cross_name'  => 'phone',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'account_phone',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'phone',
            ),

            'sms_notify'          => array('type' => 'checkbox', 'label' => 'Да/Нет'),
            'sms_notify_visit'    => array('type' => 'checkbox', 'label' => 'Да/Нет'),
            'sms_notify_change'   => array('type' => 'checkbox', 'label' => 'Да/Нет'),
            'sms_notify_news'     => array('type' => 'checkbox', 'label' => 'Да/Нет'),
            'email_notify_visit'  => array('type' => 'checkbox', 'label' => 'Да/Нет'),
            'email_notify_bonus'  => array('type' => 'checkbox', 'label' => 'Да/Нет'),
            'email_notify_change' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),

        'generator' => array(
            'fields' => array(
                'id'                  => 'ID',
                'account_id'          => 'Пользователь',
                'sms_notify_phone_id' => 'Номер телефона',
                'sms_notify'          => 'SMS-уведомления',
                'sms_notify_visit'    => 'SMS-уведомления визитов',
                'sms_notify_change'   => 'SMS-уведомления изменений в расписании',
                'sms_notify_news'     => 'SMS-уведомления о новостях',
                'email_notify_visit'  => 'EMAIL-уведомления визитов',
                'email_notify_bonus'  => 'EMAIL-уведомления о бонусах',
                'email_notify_change' => 'EMAIL-уведомления об изменениях',
            ),
            'list'   => array(
                'fields'  => array(
                    'account_id',
                    'sms_notify_phone_id',
                    'sms_notify',
                    'sms_notify_visit',
                    'sms_notify_change',
                    'sms_notify_news',
                    /*'email_notify_visit',
                    'email_notify_bonus',
                    'email_notify_change',*/
                ),
                'title'   => 'Список',
                'sort_by' => array(
                    array(
                        'field' => 'account_id',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные' => array(
                        'account_id',
                        'sms_notify_phone_id',
                        'sms_notify',
                        'sms_notify_visit',
                        'sms_notify_change',
                        'sms_notify_news',
                        /*'email_notify_visit',
                        'email_notify_bonus',
                        'email_notify_change',*/
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Данные' => array(
                        'account_id',
                        'sms_notify_phone_id',
                        'sms_notify',
                        'sms_notify_visit',
                        'sms_notify_change',
                        'sms_notify_news',
                        /*'email_notify_visit',
                        'email_notify_bonus',
                        'email_notify_change',*/
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('notification_settings', $cms_notification_settings);