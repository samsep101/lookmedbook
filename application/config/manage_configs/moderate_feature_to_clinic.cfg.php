<?php
    $moderate_feature_to_clinic = array(
        'table'  => DB_PREFIX . 'moderate_feature_to_clinic',
        'title'  => 'Сервисы клиник',
        'fields' => array(
            'id'          => 'index',
            'feature_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'feature',
                'first'       => array(
                    '0' => '',
                ),
                'sort_by'     => 'name',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('moderate_features_to_clinic', $moderate_feature_to_clinic);