<?php
    $family_relation_moderate = array(
        'table'     => DB_PREFIX . 'family_relation_moderate',
        'title'     => 'Семейные отношения для подтверждения',
        'fields'    => array(
            'id'                        => 'index',
            'account_id'                => array(
                'type'        => 'category',
                'cross_name'  => 'full_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'account',
                'first'       => array(
                    '' => '',
                ),
                'sort_by'     => 'full_name',
            ),
            'full_name'                 => 'input',
            'to_account_id'             => array(
                'type'        => 'category',
                'cross_name'  => 'full_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'account',
                'first'       => array(
                    '' => '',
                ),
                'sort_by'     => 'full_name',
            ),
            'family_relation_status_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'family_relation_status',
                'first'       => array(
                    '' => '',
                ),
                'sort_by'     => 'name',
            ),
            'is_confirmed'              => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),
        'generator' => array(
            //'disabled' => array('add','delete'),
            //'required' => array('name'=>'text'),
            'fields' => array(
                'id'                        => 'ID',
                'account_id'                => 'Пользователь',
                'full_name'                 => 'Имя родственника',
                'to_account_id'             => 'Связанный пользователь',
                'family_relation_status_id' => 'Семейный статус',
                'is_confirmed'              => 'Подтверждение',
            ),
            'list'   => array(
                'fields'  => array('account_id', 'full_name', 'to_account_id', 'family_relation_status_id', 'is_confirmed'),
                'title'   => 'Список отношений',
                'sort_by' => array(
                    array(
                        'field' => 'account_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'account_id', 'full_name', 'to_account_id', 'family_relation_status_id', 'is_confirmed'
                    ),
                ),
                'title'  => 'Редактирование связей',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'account_id', 'full_name', 'to_account_id', 'family_relation_status_id', 'is_confirmed'
                    ),
                ),
                'title'  => 'Добавление связи',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('family_relation_moderate', $family_relation_moderate);