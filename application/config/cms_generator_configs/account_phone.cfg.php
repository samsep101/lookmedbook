<?php
    $cms_phone = array(
        'table'     => DB_PREFIX . 'account_phone',
        'title'     => 'Телефоны пользователей',
        'fields'    => array(
            'id'           => 'index',
            'phone'        => 'input',
            'account_id'   => array(
                'type'        => 'category',
                'cross_name'  => 'full_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'account',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
            ),
            'code'         => 'input',
            'dt'           => 'date',
            'is_confirmed' => array('type' => 'checkbox', 'label' => 'Да/Нет')
        ),

        'generator' => array(
            'fields' => array(
                'id'           => 'ID',
                'phone'        => 'Телефонный номер',
                'account_id'   => 'ФИО пользователя',
                'code'         => 'Код подтверждения',
                'dt'           => 'Время подтверждения',
                'is_confirmed' => 'Подтвержден',
            ),
            'list'   => array(
                'fields'  => array(
                    'phone',
                    'account_id',
                    'code',
                    'dt',
                    'is_confirmed',
                ),
                'title'   => 'Список телефонных номеров',
                'sort_by' => array(
                    array(
                        'field' => 'account_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные аккаунта' => array(
                        'phone',
                        'account_id',
                        'code',
                        'dt',
                        'is_confirmed',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Тип аккаунта' => array(
                        'phone',
                        'account_id',
                        'code',
                        'dt',
                        'is_confirmed',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Создать новый',
                'submit'  => 'Создать новый',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('account_phone', $cms_phone);