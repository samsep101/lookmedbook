<?php
    $clinic_review = array(
        'table'     => DB_PREFIX . 'clinic_review',
        'title'     => 'Отзывы о клиниках',
        'fields'    => array(
            'id'                    => 'index',
            'clinic_review_text'    => 'text',
            'dt'                    => 'date',
            'visit_id'              => array(
                'type'        => 'category',
                'cross_name'  => 'id',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'visit',
                'first'       => array(
                    '0' => '',
                ),
                'sort_by'     => 'id',
            ),
            'account_id' => array(
                'type'          => 'category',
                'cross_name'    => 'full_name',
                'cross_index'   => 'id',
                'cross_table'   => DB_PREFIX . 'account',
                'first' => array(
                    '0' => '',
                ),
                'sort_by' => 'full_name',
            ),
            'author_name' => 'input',
            'clinic_id' => array(
                'type' => 'category',
                'cross_name'    => 'name',
                'cross_index'   => 'id',
                'cross_table'   => DB_PREFIX . 'clinic',
                'sort_by'       => 'name',
            ),
            'is_confirmed' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
            'waiting_time' => array(
                'type' => 'listvalue',
                'values' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5
                ),
                'selected' => 5,
            ),
            'relationship' => array(
                'type' => 'listvalue',
                'values' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5
                ),
                'selected' => 5,
            ),
            'value_for_money' => array(
                'type' => 'listvalue',
                'values' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5
                ),
                'selected' => 5,
            ),
            'diagnosis_is_clear'=> array(
                'type' => 'listvalue',
                'values' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5
                ),
                'selected' => 5,
            ),
            'service_at_the_reception' => array(
                'type' => 'listvalue',
                'values' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5
                ),
                'selected' => 5,
            )
        ),
        'generator' => array(
            'fields' => array(
                'id'                        => 'ID',
                'clinic_review_text'        => 'Текст отзыва',
                'dt'                        => 'Дата',
                'clinic_id'                 => 'Клиника',
                'account_id'                => 'Аккаунт',
                'author_name'               => 'Имя автора (используется, если не указан аккаунт)',
                'visit_id'                  => 'Визит',
                'is_confirmed'              => 'Подтвержден',
                'waiting_time'              => 'Время ожидания',
                'relationship'              => 'Отношение к пациенту ',
                'value_for_money'           => 'Соответствие цене ',
                'diagnosis_is_clear'        => 'Диагноз и дальнейшее лечения ясны? ',
                'service_at_the_reception'  => 'Обслуживание на ресепшене',
            ),
            'list'   => array(
                'fields'  => array('id', 'clinic_id', 'clinic_review_text', 'account_id', 'visit_id', 'dt', 'is_confirmed'),
                'title'   => 'Отзывы о клиниках',
                'where' => [
                    'clinic_id >' => 0,
                ],
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
                        'clinic_review_text',
                        'dt',
                        'clinic_id',
                        'account_id',
                        'author_name',
                        'visit_id',
                        'is_confirmed',
                        'waiting_time',
                        'relationship',
                        'value_for_money',
                        'diagnosis_is_clear',
                        'service_at_the_reception',
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'clinic_review_text',
                        'dt',
                        'clinic_id',
                        'account_id',
                        'author_name',
                        'visit_id',
                        'is_confirmed',
                        'waiting_time',
                        'relationship',
                        'value_for_money',
                        'diagnosis_is_clear',
                        'service_at_the_reception',
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('clinic_review', $clinic_review);
