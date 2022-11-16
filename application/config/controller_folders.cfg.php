<?php
    $controller_folders = array('admin', 'api', 'manage', 'registry', 'shop', 'widget_api', 'atlas');
    Register::add('controller_folders', $controller_folders);

    $default_controllers = array(
        'admin' => 'CmsGenerator',
        'manage' => 'CmsGenerator',
    );
    Register::add('default_controllers', $default_controllers);

    $config_folders = array(
        'admin' => 'cms_generator_configs',
        'manage' => 'manage_configs'
    );

    Register::add('config_folders', $config_folders);
