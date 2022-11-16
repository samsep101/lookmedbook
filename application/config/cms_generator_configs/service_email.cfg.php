<?php
$service_email = array(
    'table'     => DB_PREFIX . 'service_email',
    'title'     => 'Рассылка: email',
    'fields'    => array(
        'id'   => 'index',
        'email' => 'input',
        'is_record'  => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        'is_cancel'  => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        'is_callback'  => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        'is_call_to_clinic'  => array('type' => 'checkbox', 'label' => 'Да/Нет'),
    ),
    'generator' => array(
        'fields' => array(
            'id'   => 'ID',
            'email' => 'Email',
            'is_record'  => 'Для уведомлений о записи',
            'is_cancel'  => 'Для уведомлений об отмене записи',
            'is_callback'  => 'Для обратной связи',
            'is_call_to_clinic'  => 'Для заказанных звонков',
        ),
        'list'   => array(
            'fields'  => array('email'),
            'title'   => 'Список email',
            'sort_by' => array(
                array(
                    'field' => 'email',
                    'desc'  => 'ASC'
                ),
            )
        ),
        'edit'   => array(
            'fields' => array(
                'Данные' => array(
                    'email','is_record','is_cancel', 'is_callback', 'is_call_to_clinic'
                ),
            ),
            'title'  => 'Редактирование',
            'submit' => 'Сохранить',
        ),
        'add'    => array(
            'fields' => array(
                'Данные' => array(
                    'email','is_record','is_cancel', 'is_callback', 'is_call_to_clinic'
                ),
            ),
            'title'  => 'Добавление',
            'submit' => 'Добавить',
        ),
    )
);

CmsGeneratorConfigRegister::add('service_email', $service_email);