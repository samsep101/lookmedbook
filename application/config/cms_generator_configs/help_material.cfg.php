<?php
    $help_material = array(
        'table'     => DB_PREFIX . 'help_material',
        'title'     => 'Материалы',
        'fields'    => array(
            'id'                => 'index',
            'title'             => 'input',
            'content'           => 'htmlarea',
            'help_subrubric_id' => array(
                'type'        => 'category',
                'cross_name'  => 'title',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'help_subrubric',
                'first'       => array(
                    '' => '',
                ),
                'sort_by'     => 'title',
            ),
            'is_active'         => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),
        'generator' => array(
            'fields' => array(
                'id'                => 'ID',
                'title'             => 'Название',
                'content'           => 'Содержание',
                'help_subrubric_id' => 'Подрубрика',
                'is_active'         => 'Выводить',
            ),
            'list'   => array(
                'fields'  => array('title', 'help_subrubric_id', 'is_active'),
                'title'   => 'Список материалов',
                'sort_by' => array(
                    array(
                        'field' => 'title',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'title', 'content', 'help_subrubric_id', 'is_active'
                    )
                ),
                'title'  => 'Редактирование материала',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'title', 'content', 'help_subrubric_id', 'is_active'
                    ),
                ),
                'title'  => 'Добавление материала',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('help_material', $help_material);