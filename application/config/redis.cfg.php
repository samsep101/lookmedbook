<?php
    $options = array(
        'namespace' => 'Application_',
        'servers'   => array(
            array('host' => '127.0.0.1', 'port' => 6379),
        )
    );

    require_once 'Rediska.php';
    $rediska = new Rediska($options);

    Register::add('redis', $rediska);