<?php
    $my_clinic = array(
        'table'     => DB_PREFIX . 'my_clinic', /*имя таблицы*/
        'title'     => 'Список связей клиника-аккаунт', /*меняется "ролей"*/
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
            'clinic_id'  => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'clinic',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'dt'         => array('type' => 'date', 'show_time' => TRUE),
        ),
        'generator' => array(
            'fields' => array(
                'id'         => 'ID',
                'account_id' => 'Аккаунт',
                'clinic_id'  => 'Клиника',
                'dt'         => 'Дата добавления',
            ),
            'list'   => array(
                'fields'  => array('account_id', 'clinic_id', 'dt'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список связей доктор-аккаунт',
                'sort_by' => array(
                    array(
                        'field' => 'clinic_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Связь' => array(
                        'account_id', 'clinic_id', 'dt'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Связь' => array(
                        'account_id', 'clinic_id', 'dt'
                    ),
                ),
                'title'  => 'Добавить',
                'submit' => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('my_clinic', $my_clinic);
