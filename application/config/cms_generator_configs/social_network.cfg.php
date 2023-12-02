<?php
    $social_network = array(
        'table'     => DB_PREFIX . 'social_network', /*имя таблицы*/
        'title'     => 'Список соц. сетей', /*меняется "ролей"*/
        'fields'    => array(
            'id'   => 'index', /*всегда*/
            'name' => 'input',
        ),
        'generator' => array(
            'fields' => array(
                'id'   => 'ID',
                'name' => 'Название',
            ),
            'list'   => array(
                'fields'  => array('name'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список контроллеров',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Соц. сетей' => array(
                        'name'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Соц. сетей' => array(
                        'name'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('social_network', $social_network);