<?php
$moderate_clinic_license = array(
    'table'     => DB_PREFIX . 'moderate_clinic_license',
    'title'     => 'Лицензия клиники',
    'fields'    => array(
        'id'                                 => 'index',
        'license_number'                     => 'input',
        'license_issue_date'                 =>  array('type' => 'date', 'format' => 'd-m-Y'),
        'license_validity_date'              =>  array('type' => 'date', 'format' => 'd-m-Y'),
    ),
);

CmsGeneratorConfigRegister::add('moderate_clinic_license', $moderate_clinic_license);