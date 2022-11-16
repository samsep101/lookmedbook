<?php
    $moderate_clinic_requisites = array(
        'table'     => DB_PREFIX . 'moderate_clinic_requisites',
        'title'     => 'Реквизиты клиники',
        'fields'    => array(
            'id'                                         => 'index',
            'name_of_bank'                               => 'input',
            'bank_bik'                                   => 'input',
            'bank_inn'                                   => 'input',
            'bank_kpp'                                   => 'input',
            'correspondent_account'                      => 'input',
            'current_account'                            => 'input',
            'ogrn'                                       => 'input',
            'legal_address'                              => 'input',
            'fact_address'                               => 'input'
        ),
    );

    CmsGeneratorConfigRegister::add('moderate_clinic_requisites', $moderate_clinic_requisites);