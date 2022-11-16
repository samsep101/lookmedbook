<?php
    $help_subrubric = array(
        'table'     => DB_PREFIX . 'help_subrubric',
        'title'     => 'Подрубрики',
        'fields'    => array(
            'id'             => 'index',
            'title'          => 'input',
            'content'        => 'htmlarea',
            'help_rubric_id' => array(
                'type'        => 'category',
                'cross_name'  => 'title',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'help_rubric',
                'first'       => array(
                    '' => '',
                ),
                'sort_by'     => 'title',
            ),
            'is_active'      => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),
        'generator' => array(
            //'disabled' => array('add','delete'),
            //'required' => array('name'=>'text'),
            'fields' => array(
                'id'             => 'ID',
                'title'          => 'Название',
                'content'        => 'Содержание',
                'help_rubric_id' => 'Рубрика',
                'is_active'      => 'Выводить',
            ),
            'list'   => array(
                'fields'  => array('title', 'help_rubric_id', 'is_active'),
                'title'   => 'Список подрубрик',
                'sort_by' => array(
                    array(
                        'field' => 'title',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Подрубрика' => array(
                        'title', 'content', 'help_rubric_id', 'is_active'
                    ),
                ),
                'title'  => 'Редактирование подрубрики',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Подрубрика' => array(
                        'title', 'content', 'help_rubric_id', 'is_active'
                    ),
                ),
                'title'  => 'Добавление подрубрики',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('help_subrubric', $help_subrubric);