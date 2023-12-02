<?php
    $clinic_services = array(
        'table'     => DB_PREFIX . 'clinic_services',
        'title'     => 'Услиги для клиник',
        'fields'    => array(
            'id'                => 'index',
            'name'              => 'input',
            'title'             => 'input',
            'alias'             => 'input',
            'genitive_name'     => 'input',
            'plural_name'       => 'input',
            'description'       => 'htmlarea',
            'perceived_as_page' => array(
                'type'  => 'checkbox',
                'label' => 'Да/нет'
            )
        ),
        'generator' => array(
            'fields' => array(
                'id'                => 'ID',
                'name'              => 'Название',
                'title'             => 'Заголовок',
                'alias'             => 'Псевдоним',
                'genitive_name'     => 'Название в родительном падеже',
                'plural_name'       => 'Название во множественном числе',
                'description'       => 'Описание',
                'perceived_as_page' => 'Воспринимать как страницу?'
            ),
            'list'   => array(
                'fields'  => array(
                    'id',
                    'name',
                    'title',
                    'alias',
                    'genitive_name',
                    'plural_name',
                    'perceived_as_page'
                ),
                'title'   => 'Услиги для клиник',
                'sort_by' => array(
                    array(
                        'field' => 'id',
                        'desc'  => 'DESC'
                    ),
                ),
            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные' => array(
                        'name',
                        'title',
                        'alias',
                        'genitive_name',
                        'plural_name',
                        'description',
                        'perceived_as_page'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Данные' => array(
                        'name',
                        'title',
                        'alias',
                        'genitive_name',
                        'plural_name',
                        'description',
                        'perceived_as_page'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('clinic_services', $clinic_services);