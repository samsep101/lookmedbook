<?php
    $family_relation = array(
        'table'     => DB_PREFIX . 'family_relation',
        'title'     => 'Семейные связи',
        'fields'    => array(
            'id'                        => 'index',
            'account1_id'               => array(
                'type'        => 'category',
                'cross_name'  => 'full_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'account',
                'first'       => array(
                    '' => '',
                ),
                'sort_by'     => 'full_name',
            ),
            'account2_id'               => array(
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
                'account1_id'               => 'Пользователь',
                'account2_id'               => 'Связанный пользователь',
                'family_relation_status_id' => 'Семейный статус',
                'is_confirmed'              => 'Подтверждение',
            ),
            'list'   => array(
                'fields'  => array('account1_id', 'account2_id', 'family_relation_status_id', 'is_confirmed'),
                'title'   => 'Список связей',
                'sort_by' => array(
                    array(
                        'field' => 'account1_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'account1_id', 'account2_id', 'family_relation_status_id', 'is_confirmed'
                    ),
                ),
                'title'  => 'Редактирование связей',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'account1_id', 'account2_id', 'family_relation_status_id', 'is_confirmed'
                    ),
                ),
                'title'  => 'Добавить',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('family_relation', $family_relation);