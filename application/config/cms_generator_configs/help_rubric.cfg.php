<?php
    $help_rubric = array(
        'table'     => DB_PREFIX . 'help_rubric',
        'title'     => 'Рубрики',
        'fields'    => array(
            'id'        => 'index',
            'title'     => 'input',
            'content'   => 'htmlarea',
            'is_active' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),
        'generator' => array(
            //'disabled' => array('add','delete'),
            //'required' => array('name'=>'text'),
            'fields' => array(
                'id'        => 'ID',
                'title'     => 'Название',
                'content'   => 'Содержание',
                'is_active' => 'Выводить',
            ),
            'list'   => array(
                'fields'  => array('title', 'is_active'),
                'title'   => 'Список рубрик',
                'sort_by' => array(
                    array(
                        'field' => 'title',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Рубрика' => array(
                        'title', 'content', 'is_active'
                    ),
                ),
                'title'  => 'Редактирование рубрики',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Рубрика' => array(
                        'title', 'content', 'is_active'
                    ),
                ),
                'title'  => 'Добавление рубрики',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('help_rubric', $help_rubric);