<?php
$moderate_doctor_certificate = array(
    'table'  => DB_PREFIX . 'moderate_doctor_certificate',
    'title'  => 'Сертификаты врача',
    'fields' => array(
        'id'          => 'index',
        'date'    =>  array('type' => 'date', 'show_time' => FALSE),
        'duration'    =>  array('type' => 'input'),
    ),
);

CmsGeneratorConfigRegister::add('moderate_doctor_certificate', $moderate_doctor_certificate);