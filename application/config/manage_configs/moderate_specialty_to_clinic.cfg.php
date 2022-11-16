<?php
$moderate_specialty_to_clinic = array(
    'table'     => DB_PREFIX . 'moderate_specialty_to_clinic',
    'title'     => 'Специализация клиники',
    'fields'    => array(
        'id'                                        => 'index',
        'is_selected'                               => 'styled_checkbox',
    ),
);

CmsGeneratorConfigRegister::add('moderate_specialty_to_clinic', $moderate_specialty_to_clinic);