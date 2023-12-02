<?php
$moderate_clinic_user = array(
    'table'  => DB_PREFIX . 'moderate_clinic_user',
    'title'  => 'Информация по брифу',
    'fields' => array(
        'id'    => 'index',
        'fio'   => array(
            'type'  => 'input',
            'style' => 'width: 630px;',
        ),
        'email' => array(
            'type'  => 'input',
            'style' => 'width: 630px;',
        ),
        'phone' => array(
            'type'  => 'input',
            'style' => 'width: 630px;',
        ),
        'site'  => array(
            'type'  => 'input',
            'style' => 'width: 630px;',
        )
    ),
);

CmsGeneratorConfigRegister::add('moderate_clinic_user', $moderate_clinic_user);