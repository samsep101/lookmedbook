<?php
$call_to_user = array(
    'table' => DB_PREFIX . 'call_to_user',
    'title' => 'Заказанные звонки',
    'fields' => array(
        'id' => 'index',
        'name' => 'just_text',
        'phone' => 'just_text',
        'dt' => 'just_text',
    ),
    'generator' => array(
        'fields' => array(
            'id' => 'ID',
            'name' => 'Имя пользователя',
            'phone' => 'Номер телефона',
            'dt' => 'Время заказа',
        ),
        'list' => array(
            'fields' => array(
                'name',
                'phone',
                'dt'
            ),
            'title' => 'Список заказанных звонков',
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
                    'name',
                    'phone',
                    'dt',
                ),
            ),
            'title' => 'Редактирование',
            'submit' => 'Сохранить',
        ),
        'add' => array(
            'fields' => array(
                'Данные' => array(
                    'name',
                    'phone',
                    'dt',
                ),
            ),
            'title' => 'Добавить',
            'submit' => 'Добавить',
        ),
    ),
);

CmsGeneratorConfigRegister::add('call_to_user', $call_to_user);