<?php
    $settings = array(
        'table'     => DB_PREFIX . 'settings',
        'title'     => 'Настройки сайта',
        'fields'    => array(
            'id'    => 'index',
            'name'  => 'input',
            'code'  => 'input',
            'value' => 'text',
            'group' => 'input',
            'type'  => 'input',
        ),
        'generator' => array(
            'fields' => array(
                'id'    => 'ID',
                'name'  => 'Название',
                'code'  => 'Код',
                'value' => 'Значение',
                'group' => 'Группа',
                'type'  => 'Тип',

            ),
            'list'   => array(
                'fields'  => array('name', 'order'),
                'title'   => 'Список настроек',
                'sort_by' => array(
                    array(
                        'field' => 'order',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Основные данные' => array(
                        'name', 'order', 'content'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Основные данные' => array(
                        'name', 'order', 'content'
                    ),
                ),
                'title'  => 'Добавление',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('settings', $settings);