<?php
// data base config
define('DB_VIDAL_PREFIX', '');
define('DB_VIDAL_HOST', 'localhost');
//define('DB_VIDAL_NAME', 'vidal');
//define('DB_VIDAL_USER', 'root');
//define('DB_VIDAL_PASSWORD', 'gfhjkm');
define('DB_VIDAL_NAME', 'lookmedb_vidal');
define('DB_VIDAL_USER', 'lookmedb_vidal');
define('DB_VIDAL_PASSWORD', 'vidal123qa');
define('DB_VIDAL_INIT', 'SET NAMES `utf8`');

Application::addClassDir(Application::getApplicationDir() . '/models/vidal', 'Manager', 'manager');
Application::addClassDir(Application::getApplicationDir() . '/models/vidal', 'Model', 'model');