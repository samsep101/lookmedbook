<?php
    $my_doctor = array(
        'table'     => DB_PREFIX . 'my_doctor', /*имя таблицы*/
        'title'     => 'Список связей доктор-аккаунт', /*меняется "ролей"*/
        'fields'    => array(
            'id'         => 'index', /*всегда*/
            'account_id' => array(
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
            'doctor_id'  => array(
                'type' => 'ajax_input',
                'cross_name' => 'full_name',
                'cross_table' => DB_PREFIX . 'doctor',
            ),
            'dt'         => array('type' => 'date', 'show_time' => TRUE),
        ),
        'generator' => array(
            'fields' => array(
                'id'         => 'ID',
                'account_id' => 'Аккаунт',
                'doctor_id'  => 'Доктор',
                'dt'         => 'Дата добавления',
            ),
            'list'   => array(
                'fields'  => array('account_id', 'doctor_id', 'dt'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список связей доктор-аккаунт',
                'sort_by' => array(
                    array(
                        'field' => 'doctor_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Связь' => array(
                        'account_id', 'doctor_id', 'dt'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Связь' => array(
                        'account_id', 'doctor_id', 'dt'
                    ),
                ),
                'title'  => 'Добавить',
                'submit' => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('my_doctor', $my_doctor);