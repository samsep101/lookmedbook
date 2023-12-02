<?php
    $specialization = array(
        'table'     => DB_PREFIX . 'specialization',
        'title'     => 'Области ' . MEDICYNY,
        'fields'    => array(
            'id'                => 'index',
            'name'              => 'input',
            'adjective_name'    => 'input',
            'alias'             => 'input',
            'main_specialty_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'specialty',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name'
            ),
            'is_alternative'    => array(
                'type'  => 'checkbox',
                'label' => 'Да/Нет'
            ),
            'specialties_count' => array(
                'type'   => 'hightlight_row',
                'colors' => array(
                    0 => '#FFE4C4'
                ),
            ),
            'for_whom'          => array(
                'type'   => 'radio',
                'values' => array(
                    '1' => 'Для детей и для взрослых',
                    '2' => 'Только для взрослых',
                    '3' => 'Только для детей'
                ),
            ),
            'view_specialties'  => 'just_text',
            'synonyms'          => array(
                'type'       => 'list_view',
                'field_name' => 'name',
            ),
        ),
        'extra'     => array(
            'synonyms'    => array(
                'table' => 'specialization_synonym',
                'title' => 'Синонимы',
                'field' => 'specialization_id',
                'where' => array(
                    'is_main' => 0
                )
            ),
            'specialties' => array(
                'table' => 'specialty_to_specialization',
                'title' => 'Специализации',
                'field' => 'specialization_id',
                'where' => array(
                    'is_main' => 0
                )
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'                => 'ID',
                'name'              => 'Название',
                'adjective_name'    => 'Прилагательное от названия (мн.ч.)',
                'alias'             => 'Псевдоним',
                'specialties_count' => ' ',
                'main_specialty_id' => 'Основная специализация',
                'is_alternative'    => 'Альт.',
                'for_whom'          => 'Взрослая/Десткая',
                'view_specialties'  => 'Специализации',
                'synonyms'          => 'Синонимы',
            ),
            'list'   => array(
                'fields'  => array(
                    'name',
                    'adjective_name',
                    'alias',
                    'synonyms',
                    'specialties_count',
                    'is_alternative',
                    'for_whom',
                    'view_specialties',
                ),
                'title'   => 'Области ' . MEDICYNY,
                'legend'  => array(
                    '#000000' => 'область '. MEDICYNY .' не имеет специализаций'
                ),
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
                        'adjective_name',
                        'alias',
                        'main_specialty_id',
                        'is_alternative',
                        'for_whom',
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'name',
                        'adjective_name',
                        'alias',
                        'main_specialty_id',
                        'is_alternative',
                        'for_whom',
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );


    CmsGeneratorConfigRegister::add('specialization', $specialization);