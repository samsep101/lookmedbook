<?php
    $my_disease = array(
        'table'     => DB_PREFIX . 'my_disease', /*имя таблицы*/
        'title'     => 'Заболевания пользователя', /*меняется "ролей"*/
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
            'disease_id' => array(
                'type'        => 'category',
                'cross_name'  => 'title',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'disease',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'title',
            ),

        ),
        'generator' => array(
            'fields' => array(
                'id'         => 'ID',
                'account_id' => 'Аккаунт',
                'disease_id' => 'Заболевание',
            ),
            'list'   => array(
                'fields'  => array('account_id', 'disease_id'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Заболевания пользователя',
                'sort_by' => array(
                    array(
                        'field' => 'disease_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'account_id', 'disease_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'account_id', 'disease_id'
                    ),
                ),
                'title'  => 'Добавить',
                'submit' => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('my_disease', $my_disease);
