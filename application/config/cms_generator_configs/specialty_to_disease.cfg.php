<?php
    $specialty_to_disease = array(
        'table'     => DB_PREFIX . 'specialty_to_disease', /*имя таблицы*/
        'title'     => 'Список связей специальность-заболевание', /*меняется "ролей"*/
        'fields'    => array(
            'id'           => 'index', /*всегда*/
            'specialty_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'specialty',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'disease_id'   => array(
                'type'        => 'category',
                'cross_name'  => 'title',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'disease',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'title',
            ),
            'is_adult'    => array(
                'type'  => 'checkbox',
                'label' => 'Да/нет'
            ),
            'is_male'    => array(
                'type'  => 'checkbox',
                'label' => 'Да/нет'
            ),
            'is_female'    => array(
                'type'  => 'checkbox',
                'label' => 'Да/нет'
            ),
            'is_children'    => array(
                'type'  => 'checkbox',
                'label' => 'Да/нет'
            ),
            'is_newborn'    => array(
                'type'  => 'checkbox',
                'label' => 'Да/нет'
            ),
            'is_pregnant'    => array(
                'type'  => 'checkbox',
                'label' => 'Да/нет'
            ),
            'main_flag'    => array(
                'type'  => 'checkbox',
                'label' => 'Да/нет'
            )
        ),
        'generator' => array(
            'fields' => array(
                'id'           => 'ID',
                'specialty_id' => 'Специальность',
                'disease_id'   => 'Заболевание',
                'main_flag'    => 'Основная специальность',
                'is_adult'    => 'Для взрослых',
                'is_male'    => 'Для мужчин',
                'is_female'    => 'Для женщин',
                'is_children'    => 'Для детей',
                'is_newborn'    => 'Для новорожденных',
                'is_pregnant'    => 'Для беременных',
            ),
            'list'   => array(
                'fields'  => array(
                    'specialty_id',
                    'disease_id',
                    'main_flag',
                    'is_adult',
                    'is_male',
                    'is_female',
                    'is_children',
                    'is_newborn',
                    'is_pregnant'
                ), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список связей специальность-заболевание',
                'sort_by' => array(
                    array(
                        'field' => 'disease_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'specialty_id',
                        'disease_id',
                        'main_flag',
                        'is_adult',
                        'is_male',
                        'is_female',
                        'is_children',
                        'is_newborn',
                        'is_pregnant'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'specialty_id',
                        'disease_id',
                        'main_flag',
                        'is_adult',
                        'is_male',
                        'is_female',
                        'is_children',
                        'is_newborn',
                        'is_pregnant'
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('specialty_to_disease', $specialty_to_disease);