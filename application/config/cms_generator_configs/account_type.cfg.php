<?php
    $cms_account_type = array(
        'table'     => DB_PREFIX . 'account_type',
        'title'     => 'Типы аккаунтов',
        'fields'    => array(
            'id'            => 'index',
            'name'          => 'input',
            'persons_count' => 'input',

            //'hash_key'			=> 	'input',

            //'make_calculation'		=> array('type' => 'checkbox','label' => 'Да/Нет'),
            //'make_plan'		=> array('type' => 'checkbox','label' => 'Да/Нет'),
            //'view_plan'		=> array('type' => 'checkbox','label' => 'Да/Нет'),
            //'start_production'		=> array('type' => 'checkbox','label' => 'Да/Нет'),


        ),
        'generator' => array(
            'fields' => array(
                'id'            => 'ID',
                'name'          => 'Тип',
                'persons_count' => 'Количество человек',
            ),
            'list'   => array(
                'fields'  => array('name', 'persons_count',),
                //'is_super'
                'title'   => 'Список типов аккаунтов',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields'  => array(
                    'Тип аккаунта' => array(
                        'name',
                        'persons_count',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Тип аккаунта' => array(
                        'name',
                        'persons_count',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Создать нового',
                'submit'  => 'Создать нового',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('account_type', $cms_account_type);