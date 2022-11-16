<?php
    $system_access_ip = array(
        'table'     => DB_PREFIX . 'system_access_ip',
        'title'     => 'Список IP для доступа к аналитической информации',
        'fields'    => array(
            'id'           => 'index',
            'ip'    => 'input',
            'is_active'    => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),
        'generator' => array(
            'fields' => array(
                'id'           => 'ID',
                'ip'    => 'IP адресс',
                'is_active' => 'Активен',
            ),
            'list'   => array(
                'fields'  => array(
                    'ip',
                    'is_active',
                ),
                'title'   => 'Список IP-адрессов',
                'sort_by' => array(
                    array(
                        'field' => 'ip',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'ip',
                        'is_active',
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'ip',
                        'is_active'
                    ),
                ),
                'title'  => 'Добавление',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('system_access_ip', $system_access_ip);