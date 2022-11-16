<?php

date_default_timezone_set('Europe/Moscow');

function __autoload_application($className)
{
  if ($className == 'Application') {
    require_once(ABS_ROOT . '/core/classes/application.class.php');
  } else {
    if ((strpos($className, 'PHPExcel') === FALSE)) {
      Application::loadClass($className);
    } else {
      PhpExcel_Autoloader::Load($className);
    }
  }
}

spl_autoload_register('__autoload_application');

require_once(ABS_ROOT . '/vendor/autoload.php');
require_once __DIR__ . '/version.php';

$application = new Application();
Application::setRootDir(ABS_ROOT);
Application::setDefaultDirs();
Application::loadConfig('db');
Application::loadConfig('db_profiler');
Application::loadConfig('db_vidal');
Application::loadConfig('map');
//    Application::loadConfig('site');
Application::loadConfig('controller_folders');
Application::loadConfig('validation_rules');
Application::loadConfig('cache');
Application::loadConfig('profiler');
Application::loadConfig('memcache');
Application::loadConfig('controller_decorators');
Application::loadConfig('elasticsearch');


Application::afterInit();

if (isset($_SERVER['REQUEST_URI'])) {
  if (strpos($_SERVER['REQUEST_URI'], 'admin') !== FALSE) {
    Application::loadAllConfigInFolder('cms_generator_configs');
  }
  if (strpos($_SERVER['REQUEST_URI'], 'registry') !== FALSE) {
    Application::loadAllConfigInFolder('manage_configs');
  }
}


if (isset($_REQUEST['admitad_uid'])){
  setcookie(
      'admitad_uid',
      $_REQUEST['admitad_uid'],
      time() + 60 * 60 * 24 * 30, // 30 days
      '/',
      '.' . SITE_DOMAIN
  );
}
if (isset($_REQUEST['utm_campaign']) && $_REQUEST['utm_campaign'] == 'mixuni'){
  setcookie(
      'utm_campaign',
      $_REQUEST['utm_campaign'],
      time() + 60 * 60 * 24 * 30, // 30 days
      '/',
      '.' . SITE_DOMAIN
  );
}





Register::add('db', new Db());
Register::add('utils', new Utils());

Register::add('SUBDOMAINS', [
    'sankt-peterburg',
    'novosibirsk',
    'chelyabinsk',
    'samara',
    'kazan',
    'nizhniy-novgorod',
    'ekaterinburg',
    'perm',
    'krasnodar',
    'voronezh',
    'rostov-na-donu',
    'ufa',
    'izhevsk',
    'krasnoyarsk',
    'volgograd',
    'sochi',
    'tyumen',
    'yaroslavl'
]);


require_once(ABS_ROOT . '/core/funcs/funcs.php');
