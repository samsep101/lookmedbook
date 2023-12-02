<?php
    $disease_understand = array(
        'table'     => DB_PREFIX . 'disease_understand',
        'title'     => 'Понятие пользователями текста заболеваний',
        'fields'    => array(
            'id'              => 'index', /*всегда*/
            'account_id'      => array(
                'type'        => 'category',
                'cross_name'  => 'full_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'account',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'last_name',
            ),
            'disease_id'      => array(
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
            'understand_flag' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),
        'generator' => array(
            'fields' => array(
                'id'              => 'ID',
                'account_id'      => 'Пользователь',
                'disease_id'      => 'Заболевание',
                'understand_flag' => 'Понял',
            ),
            'list'   => array(
                'fields'  => array('disease_id', 'account_id', 'understand_flag'),
                'title'   => 'Понятие пользователями текста заболеваний',
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
                        'disease_id', 'account_id', 'understand_flag'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'disease_id', 'account_id', 'understand_flag'
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('disease_understand', $disease_understand);