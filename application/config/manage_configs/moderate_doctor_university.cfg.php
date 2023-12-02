<?php
$moderate_doctor_university = array(
    'table'  => DB_PREFIX . 'moderate_doctor_university',
    'title'  => 'ВУЗ/СУЗ врача',
    'fields' => array(
        'id'          => 'index',
        'high_education_end_year'    =>  array(
            'type' => 'input'
        ),
        'secondary_education_end_year'    =>  array(
            'type' => 'input'
        ),
        'high_education_specialty_id' => array(
            'type'	=>	'listvalue',
            'values'	=>	array(
                '0' => '',
                '1' => 'Лечебное дело',
                '2' => 'Стоматология',
                '3' => 'Педиатрия'
            )
        ),
        'secondary_education_specialty_id' => array(
            'type'	=>	'category',
            'cross_table' => DB_PREFIX . 'secondary_education_specialty',
            'cross_index' => 'id',
            'cross_name' => 'name',
            'first' => array(
                '0' => '',
            ),
            'filter' => 'true',
            'sort_by' => 'name',
            'style' => 'width: 100%',
        ),
    ),
);

CmsGeneratorConfigRegister::add('moderate_doctor_university', $moderate_doctor_university);