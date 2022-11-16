<?php
    $cms_account_session = array(
        'table'     => DB_PREFIX . 'account_session',
        'title'     => 'Сессии аккаунтов',
        'fields'    => array(
            'id'         => 'index',
            'account_id' => array(
                'type'        => 'category',
                'cross_name'  => 'login',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'account',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'login',
            ),
            'ip'         => 'input',
            'dt'         => array('type' => 'date', 'show_time' => TRUE),
        ),

        'generator' => array(
            'fields' => array(
                'id'         => 'ID',
                'account_id' => 'Аккаунт',
                'ip'         => 'IP',
                'dt'         => 'Дата',
                //'hash_key'		=>	'Ключ рабочего места',
            ),

            'list'   => array(
                'fields'  => array('id', 'account_id', 'ip', 'dt'),
                //'is_super'
                'title'   => 'Список сессий',
                'sort_by' => array(
                    array(
                        'field' => 'account_id',
                        'desc'  => 'ASC'
                    ),
                )

            ),

            'edit'   => array(
                'fields' => array(
                    'Основные данные' => array(
                        'account_id',
                        'ip',
                        'dt',
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),

            'add'    => array(
                'fields' => array(
                    'Основные данные' => array(
                        'account_id',
                        'ip',
                        'dt',
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('account_session', $cms_account_session);