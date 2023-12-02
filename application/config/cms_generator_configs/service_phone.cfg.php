<?php
$service_phone = array(
    'table'     => DB_PREFIX . 'service_phone',
    'title'     => 'Рассылка: телефоны',
    'fields'    => array(
        'id'   => 'index',
        'phone' => 'input',
        'is_record'  => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        'is_cancel'  => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        'is_call_to_clinic'  => array('type' => 'checkbox', 'label' => 'Да/Нет'),
    ),
    'generator' => array(
        'fields' => array(
            'id'   => 'ID',
            'phone' => 'Телефон',
            'is_record'  => 'Для уведомлений о записи',
            'is_cancel'  => 'Для уведомлений об отмене записи',
            'is_call_to_clinic'  => 'Для заказанных звонков'
        ),
        'list'   => array(
            'fields'  => array('phone'),
            'title'   => 'Список телефонов',
            'sort_by' => array(
                array(
                    'field' => 'phone',
                    'desc'  => 'ASC'
                ),
            )
        ),
        'edit'   => array(
            'fields' => array(
                'Данные' => array(
                    'phone', 'is_record', 'is_cancel', 'is_call_to_clinic'
                ),
            ),
            'title'  => 'Редактирование',
            'submit' => 'Сохранить',
        ),
        'add'    => array(
            'fields' => array(
                'Данные' => array(
                    'phone', 'is_record', 'is_cancel', 'is_call_to_clinic'
                ),
            ),
            'title'  => 'Добавление',
            'submit' => 'Добавить',
        ),
    )
);

CmsGeneratorConfigRegister::add('service_phone', $service_phone);