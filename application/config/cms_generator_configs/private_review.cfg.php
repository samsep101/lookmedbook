<?php
    $private_review = array(
        'table'     => DB_PREFIX . 'private_review',
        'title'     => 'Приватные сообщения',
        'fields'    => array(
            'id'           => 'index',
            'text'         => 'text',
            'dt'           => 'date',
            'visit_id'     => array(
                'type'        => 'category',
                'cross_name'  => 'id',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'visit',
                'first'       => array(
                    '0' => '',
                ),
                'sort_by'     => 'id',
            ),
            'account_id'   => array(
                'type'        => 'category',
                'cross_name'  => 'full_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'account',
                'first'       => array(
                    '0' => '',
                ),
                'sort_by'     => 'full_name',
            ),
            'is_confirmed' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),
        'generator' => array(
            'fields' => array(
                'id'           => 'ID',
                'text'         => 'Текст',
                'dt'           => 'Дата',
                'account_id'   => 'Аккаунт',
                'visit_id'     => 'Визит',
                'is_confirmed' => 'Подтвержден',
            ),
            'list'   => array(
                'fields'  => array('account_id', 'visit_id', 'dt', 'is_confirmed'),
                'title'   => 'Список научных работ',
                'sort_by' => array(
                    array(
                        'field' => 'dt',
                        'desc'  => 'DESC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'text', 'dt', 'account_id', 'visit_id', 'is_confirmed'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'text', 'dt', 'account_id', 'visit_id', 'is_confirmed'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('private_review', $private_review);