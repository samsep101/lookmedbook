<?php
$moderate_doctor_education = array(
    'table'  => DB_PREFIX . 'moderate_doctor_education',
    'title'  => 'Интернатура/ординатура врача',
    'fields' => array(
        'id'          => 'index',
        'end_year'    =>  array('type' => 'date', 'show_time' => FALSE),
        'doctor_education_type_id' => array(
            'type'	=>	'listvalue',
            'values'	=>	array(
                '0' => '',
                '1' => 'Интернатура',
                '2' => 'Ординатура'
            )
        ),
    ),
);

CmsGeneratorConfigRegister::add('moderate_doctor_education', $moderate_doctor_education);