<?php
$moderate_clinic_card_image = array(
    'table'     => DB_PREFIX . 'moderate_clinic_card_image',
    'title'     => 'Фотографии клиники',
    'fields'    => array(
        'id'                                         => 'index',
        'card_image_id' => array(
            'type'        => 'category',
            'cross_name'  => 'filename',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'image',
            'first'       => array(
                '' => '',
            ),
            'filter'      => 'true',
            'sort_by'     => 'filename',
        ),
    ),
);

CmsGeneratorConfigRegister::add('moderate_clinic_card_image', $moderate_clinic_card_image);