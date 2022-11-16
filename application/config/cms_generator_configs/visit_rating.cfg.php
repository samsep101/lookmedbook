<?php
    $visit_rating = array(
        'table' => DB_PREFIX . 'visit_rating', /*имя таблицы*/
        'title' => 'Оценки визита', /*меняется "ролей"*/
        'fields' => array(
            'id' => 'index', /*всегда*/
            'visit.id' => array(
                'type' => 'just_text'
            ),
            'visit_id' => array(
                'type' => 'category',
                'cross_name' => 'id',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'visit',
                'first' => array(
                    '0' => '',
                ),
                'filter' => 'true',
                'sort_by' => 'id',
            ),
            'cabinet' => array(
                'type' => 'listvalue',
                'values' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5
                ),
            ),
            'waiting_time' => array(
                'type' => 'listvalue',
                'values' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5
                ),
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
            ),
            'doctor.full_name' => array(
                'type' => 'just_text'
            ),
            'clinic.name' => 'just_text',
            'account.full_name' => 'just_text',
            'is_doctor_advice' => 'checkbox',
            'is_clinic_advice' => 'checkbox',
            'doctor_review_text' => array(
                'type' => 'htmlarea'
            ),
            'clinic_review_text' => array(
                'type' => 'htmlarea'
            ),
            'private_review_text' => array(
                'type' => 'htmlarea'
            ),
            'is_confirmed' => array(
                'type' => 'checkbox'
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id' => 'ID',
                'visit_id' => 'Визит',
                'visit.id' => 'Визит',
                'doctor.full_name' => 'Доктор',
                'clinic.name' => 'Клиника',
                'account.full_name' => 'Пользователь',
                'cabinet' => 'Оценка кабинета',
                'waiting_time' => 'Время ожидания',
                'relationship' => 'Отношение к пациенту ',
                'value_for_money' => 'Соответствие цене ',
                'diagnosis_is_clear' => 'Диагноз и дальнейшее лечения ясны? ',
                'service_at_the_reception' => 'Обслуживание на ресепшене',
                'is_doctor_advice' => 'Посоветует доктора друзьям?',
                'is_clinic_advice' => 'Посоветует клинику друзьям?',
                'doctor_review_text' => 'Отзыв о враче',
                'clinic_review_text' => 'Отзыв о клинике',
                'private_review_text' => 'Приватный отзыв для '.SITE_NAME,
                'is_confirmed' => 'Подтвержден',

            ),
            'list' => array(
                'fields' => array(
                    'visit_id',
                    'doctor.full_name',
                    'clinic.name',
                    'account.full_name'
                ),
                'title' => 'Оценки визита',
                'sort_by' => array(
                    array(
                        'field' => 'visit_id',
                        'desc' => 'DESC'
                    ),
                )
            ),
            'edit' => array(
                'fields' => array(
                    'Оценки' => array(
                        'visit.id',
                        'doctor.full_name',
                        'clinic.name',
                        'account.full_name',
                        'cabinet',
                        'waiting_time',
                        'relationship',
                        'value_for_money',
                        'diagnosis_is_clear',
                        'service_at_the_reception',
                        'is_doctor_advice',
                        'is_clinic_advice',
                        'is_confirmed',
                    ),
                    'Отзывы' => array(
                        'doctor_review_text',
                        'clinic_review_text',
                        'private_review_text'
                    ),
                ),
                'title' => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add' => array(
                'fields' => array(
                    'Оценки' => array(
                        'visit_id',
                        'cabinet',
                        'waiting_time',
                        'relationship',
                        'value_for_money',
                        'diagnosis_is_clear',
                        'service_at_the_reception',
                        'is_doctor_advice',
                        'is_clinic_advice',
                        'is_confirmed',
                    ),
                    'Отзывы' => array(
                        'doctor_review_text',
                        'clinic_review_text',
                        'private_review_text'
                    ),
                ),
                'title' => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('visit_rating', $visit_rating);