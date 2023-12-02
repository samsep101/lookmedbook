<?php
    $page = array(
        'table'     => DB_PREFIX . 'page',
        'title'     => 'Страницы',
        'fields'    => array(
            'id'        => 'index',
            'parent_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'page',
                'first'       => array(
                    '' => '',
                ),
                'sort_by'     => 'name',
            ),
            'name'      => 'input',
            'descr'     => 'htmlarea',
            'content'   => 'htmlarea',
            'sort'      => 'input',
            'title'     => 'input',
            'kwords'    => 'input',
            'is_active' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),
        'generator' => array(
            'fields'   => array(
                'id'        => 'ID',
                'parent_id' => 'Рубрика',
                'name'      => 'Название',
                'descr'     => 'Описание',
                'content'   => 'Содержание',
                'sort'      => 'Сортировка',
                'title'     => 'SEO: Title',
                'kwords'    => 'SEO: Ключевые слова',
                'is_active' => 'Выводить',
            ),
            'list'     => array(
                'fields'  => array('name', 'parent_id', 'sort', 'is_active'),
                'title'   => 'Список страниц',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'     => array(
                'fields' => array(
                    'Основные данные' => array(
                        'name', 'parent_id', 'descr', 'content', 'sort', 'is_active'
                    ),
                    'SEO'             => array(
                        'title', 'kwords'
                    ),
                ),
                'title'  => 'Редактирование страницы',
                'submit' => 'Сохранить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('page', $page);