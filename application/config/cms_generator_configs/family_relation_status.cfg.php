<?php
    $family_relation_status = array(
        'table'     => DB_PREFIX . 'family_relation_status', /*имя таблицы*/
        'title'     => 'Список семейных статусов', /*меняется "ролей"*/
        'fields'    => array(
            'id'   => 'index', /*всегда*/
            'name' => 'input',
        ),
        'generator' => array(
            'fields' => array(
                'id'   => 'ID',
                'name' => 'Название статуса',
            ),
            'list'   => array(
                'fields'  => array('name'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список семейных статусов',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Статус' => array(
                        'name'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Статус' => array(
                        'name'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('family_relation_status', $family_relation_status);