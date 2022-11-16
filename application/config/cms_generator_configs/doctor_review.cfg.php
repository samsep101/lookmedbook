<?php
$doctor_review = array(
    'table' => DB_PREFIX . 'visit_rating',
    'title' => 'Отзывы о врачах',
    'fields' => array(
        'id' => 'index',
        'doctor_review_text' => 'text',
        'dt' => 'date',
        'visit_id' => array(
            'type' => 'category',
            'cross_name' => 'id',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'visit',
            'first' => array(
                '0' => '',
            ),
            'sort_by' => 'id',
            'where' => array(
                'param' => 'status_id',
            	'value' => 7,
            ),
        ),
        'account_id' => array(
            'type' => 'category',
            'cross_name' => 'full_name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'account',
            'first' => array(
                '0' => '',
            ),
            'sort_by' => 'full_name',
        ),
        'author_name' => 'input',
        'doctor_id' => array(
            'type' => 'ajax_input',
            'cross_name' => 'full_name',
            'cross_table' => DB_PREFIX . 'doctor',
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
        ),
    ),
    'generator' => array(
        'fields' => array(
            'id' => 'ID',
            'doctor_review_text' => 'Текст отзыва',
            'dt' => 'Дата',
            'doctor_id' => 'Доктор',
            'account_id' => 'Аккаунт',
            'author_name' => 'Имя автора (используется, если не указан аккаунт)',
            'visit_id' => 'Визит',
            'is_confirmed' => 'Подтвержден',
            'waiting_time' => 'Время ожидания',
            'relationship' => 'Отношение к пациенту ',
            'value_for_money' => 'Соответствие цене ',
            'diagnosis_is_clear' => 'Диагноз и дальнейшее лечения ясны? ',
            'service_at_the_reception' => 'Обслуживание на ресепшене',
        ),
        'list' => array(
            'fields' => array('id', 'doctor_id', 'doctor_review_text', 'account_id', 'visit_id', 'dt', 'is_confirmed'),
            'title' => 'Отзывы о врачах',
            'where' => [
                'doctor_id >' => 0,
            ],
            'sort_by' => array(
                array(
                    'field' => 'dt',
                    'desc' => 'DESC'
                ),
            ),
        ),
        'edit' => array(
            'fields' => array(
                'Данные' => array(
                    'doctor_review_text',
                    'dt',
                    'doctor_id',
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
            'title' => 'Редактирование',
            'submit' => 'Сохранить',
        ),
        'add' => array(
            'fields' => array(
                'Данные' => array(
                    'doctor_review_text',
                    'dt',
                    'doctor_id',
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
            'title' => 'Добавить',
            'submit' => 'Добавить',
        ),
    ),
);

CmsGeneratorConfigRegister::add('doctor_review', $doctor_review);
