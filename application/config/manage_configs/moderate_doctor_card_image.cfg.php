<?php
    $moderate_doctor_card_image = array(
        'table'  => DB_PREFIX . 'moderate_doctor_card_image',
        'title'  => 'Изображение карточки врача',
        'fields' => array(
            'id'               => 'index',
            'card_image_id'  =>  array(
                'type'        => 'category',
                'cross_name'  => 'filename',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'image',
                'first'       => array(
                    '' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'filename',
                'style'  	=> ''
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('moderate_doctor_card_image', $moderate_doctor_card_image);