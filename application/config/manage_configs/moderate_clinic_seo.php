<?php
$moderate_clinic_seo = array(
    'table'  => DB_PREFIX . 'moderate_clinic_seo',
    'title'  => 'SEO',
    'fields' => array(
        'id'    => 'index',
        'metro_to_title'   => array(
            'type'  => 'styled_checkbox',
        ),
        'address_to_title'   => array(
            'type'  => 'styled_checkbox',
        ),
        'metro_to_description'   => array(
            'type'  => 'styled_checkbox',
        ),
        'address_to_description'   => array(
            'type'  => 'styled_checkbox',
        ),
        'seo_title' => array(
            'type'  => 'input',
            'style' => 'width:100%; box-sizing: border-box;'
        ),
        'seo_descritpion'   => array(
            'type'  => 'text',
            'style' => 'width:100%; box-sizing: border-box; height: 90px; padding: 20px 10px;'
        ),
        'seo_address'   => array(
            'type'  => 'input',
            'style' => 'width:100%; box-sizing: border-box; margin-bottom:20px'
        )
    ),
);

CmsGeneratorConfigRegister::add('moderate_clinic_seo', $moderate_clinic_seo);