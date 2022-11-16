<?php
    $moderate_clinic_description = array(
        'table'  => DB_PREFIX . 'moderate_clinic_description',
        'title'  => 'Реквизиты клиники',
        'fields' => array(
            'id'   => 'index',
            'about' => 'htmlarea'
        ),
    );

    CmsGeneratorConfigRegister::add('moderate_clinic_description', $moderate_clinic_description);