<?php
    $clinic_type = array(
        'table'     => DB_PREFIX . 'clinic_type',
        'title'     => 'Типы для клиники',
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
                    'name',
                    'title',
                    'alias',
                    'genitive_name',
                    'plural_name',
                    'description',
                    'perceived_as_page'
                ),
                'title'   => 'Список',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
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
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
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
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );
    CmsGeneratorConfigRegister::add('clinic_type', $clinic_type);
