<?php

    $cms_account_user = array(
        'table'     => DB_PREFIX . 'account_user',
        'title'     => 'Пользователи аккаунтов',
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
            //'hash_key'			=> 	'input',

            //'make_calculation'		=> array('type' => 'checkbox','label' => 'Да/Нет'),
            //'make_plan'		=> array('type' => 'checkbox','label' => 'Да/Нет'),
            //'view_plan'		=> array('type' => 'checkbox','label' => 'Да/Нет'),
            //'start_production'		=> array('type' => 'checkbox','label' => 'Да/Нет'),

            'name'       => 'input',
            'last_name'  => 'input',
            'sex'        => array(
                'type'   => 'listvalue',
                'values' => array(
                    '1' => 'Мужской',
                    '2' => 'Женский',
                ),
                'filter' => 'true',
            ),
            'birthday'   => 'date',
        ),

        'generator' => array(
            /*'required' => array('login'=>'exist','password'=>'text','email'=>'email','role_id'=>'text'),*/
            'fields' => array(
                'id'         => 'ID',
                'account_id' => 'Аккаунт',
                //'hash_key'		=>	'Ключ рабочего места',
                'name'       => 'Имя',
                'last_name'  => 'Фамилия',
                'sex'        => 'Пол',
                'birthday'   => 'Дата рождения',

            ),

            'list'   => array(
                'fields'  => array('last_name', 'name', 'sex', 'birthday', 'account_id',),
                //'is_super'
                'title'   => 'Список пользователей аккаунтов',
                'sort_by' => array(
                    array(
                        'field' => 'last_name',
                        'desc'  => 'ASC'
                    ),
                )
            ),

            'edit'   => array(
                'fields'  => array(
                    'Основные данные' => array(
                        'last_name',
                        'name',
                        'sex',
                        'birthday',
                        'account_id',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),

            'add'    => array(
                'fields'  => array(
                    'Основные данные' => array(
                        'last_name',
                        'name',
                        'sex',
                        'birthday',
                        'account_id',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Создать нового',
                'submit'  => 'Создать нового',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('account_user', $cms_account_user);