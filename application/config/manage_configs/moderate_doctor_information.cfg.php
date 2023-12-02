<?php
    $moderate_doctor_information = array(
        'table'  => DB_PREFIX . 'moderate_doctor_information',
        'title'  => 'О враче',
        'fields' => array(
            'id'                 => 'index',
            'full_name'          => array(
                'type'        => 'category',
                'cross_table' => DB_PREFIX . 'doctor',
                'cross_index' => 'id',
                'cross_name'  => 'full_name',
                'first'       => array(
                    '0' => '',
                ),
                'style'       => 'width: 100%',
                'sort_by'     => 'last_name',
            ),
            'last_name'          => 'input',
            'first_name'         => 'input',
            'second_name'        => 'input',
            'rate'               => array(
                'type'  => 'input',
                'style' => 'width: 50px',
            ),
            'work_experience'    => array(
                'type'  => 'input',
                'style' => 'width: 50px',
            ),
            'sex_id'             => array(
                'type' => 'sex_select',
            ),
            'is_leave_the_house' => array(
                'type'  => 'styled_checkbox',
                'label' => 'Выезжает на дом'
            ),
            'is_adult'           => array(
                'type'  => 'styled_checkbox',
                'label' => 'Взрослых'
            ),
            'is_children'        => array(
                'type'  => 'styled_checkbox',
                'label' => 'Детей'
            ),
            'is_pregnant'        => array(
                'type'  => 'styled_checkbox',
                'label' => 'Беременных'
            ),
            'is_handicapped'     => array(
                'type'  => 'styled_checkbox',
                'label' => 'Инвалидов'
            ),
            'not_work'     => array(
                'type'  => 'styled_checkbox',
                'label' => 'Не работаем с врачом'
            ),
            'is_active'     => array(
                'type'  => 'styled_checkbox',
                'label' => 'Врач опубликован на '.SITE_NAME,
            ),
            'about_internal'     => 'htmlarea',
            'about'              => 'htmlarea',
            'education'          => 'htmlarea',
            'course'             => 'htmlarea',
            'certificate'        => 'htmlarea',
            'academic_title'     => 'htmlarea',
        ),
    );

    CmsGeneratorConfigRegister::add('moderate_doctor_information', $moderate_doctor_information);
