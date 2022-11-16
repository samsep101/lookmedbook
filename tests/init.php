<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'base.php';

chdir(dirname(__DIR__));

function load($className)
{
    if ($className == 'Application') {
        require_once('core/classes/application.class.php');
    } else {
        Application::loadClass($className);
    }
}

spl_autoload_register('load', true);

Application::setDefaultDirs();
Application::loadConfig('db');
Application::loadConfig('memcache');

Register::add('db', new Db());
