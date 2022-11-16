<?php

class Application
{

  private static $rootDir = '.';
  private static $applicationDir = 'application';
  private static $classDirs = NULL;
  private static $configDirs = NULL;
  private static $templatesDir = 'templates';
  private static $coreDir = 'core';
  private static $controllersDir = 'controllers';
  private static $uploadDir = 'media/files';
  private static $httpRoot = '/';

  /**
   * Initializing of appplication
   *
   * 1. Session start
   * 2. Register globals turn off (ensure)
   * 3. Magic quotes turn off (ensure)
   */
  public function __construct()
  {
    // todo: костыль
    if (isset($_SERVER['REQUEST_URI']) and ($_SERVER['REQUEST_URI'] == '/account/ajaxLogin') && !isset($_POST['remember'])) {
      session_set_cookie_params(5);
    }

    ini_set('register_globals', 0);

    if (get_magic_quotes_runtime() || get_magic_quotes_gpc()) {
      $this->stripParametres($_REQUEST);
      $this->stripParametres($_POST);
      $this->stripParametres($_GET);
    }
  }

  public static function afterInit()
  {
    if (php_sapi_name() == 'cli') {
      return;
    }

    if (isset($_COOKIE['LMDSSSN']) && isset($_COOKIE['LMDHOST']) && ($_COOKIE['LMDHOST'] == SITE_URL)) {
      $session_id = $_COOKIE['LMDSSSN'];
      session_id($session_id);
      session_start();
    } else {
      session_start();
      $url = str_replace(SITE_SCHEME . '://', '', SITE_URL);
      setcookie('LMDSSSN', session_id(), time() + 30 * 24 * 60 * 60, null, '.' . $url);
      setcookie('LMDHOST', SITE_URL, time() + 30 * 24 * 60 * 60, null, '.' . $url);
    }
  }

  /**
   * Set file system path to application root without tralling slash.
   * "/" should be used as directory separator.
   * By default, it's ".".
   *
   * @param string $path path to application root
   */
  public static function setRootDir($path)
  {
    self::$rootDir = $path;
  }

  /**
   * Get file system path to application root without tralling slash.
   * "/" will be used as directory separator.
   * @return string path to application root
   */
  public static function getRootDir()
  {
    return self::$rootDir;
  }

  /**
   * Set file system path to core directory relative from application root
   * without starting and tralling slashes.
   * "/" should be used as directory separator.
   * By default, it's "core".
   *
   * @param string $path path to core directory
   */
  public static function setCoreDir($path)
  {
    self::$coreDir = $path;
  }

  public static function getCoreDir()
  {
    return self::$coreDir;
  }

  /**
   * Set file system path to application directory relative from application root
   * without starting and tralling slashes.
   * "/" should be used as directory separator.
   * By default, it's "application".
   *
   * @param string $path path to application directory
   */
  public static function setApplicationDir($path)
  {
    self::$applicationDir = $path;
  }

  public static function getApplicationDir()
  {
    return self::$applicationDir;
  }

  /**
   * Set file system path to controllers directory relative from application directory
   * without starting and tralling slashes.
   * "/" should be used as directory separator.
   * By default, it's "controllers".
   *
   * @param string $path path to controllers directory
   */
  public static function setControllersDir($path)
  {
    self::$controllersDir = $path;
  }

  public static function getControllersDir()
  {
    return self::$controllersDir;
  }

  /**
   * Set http application path with starting and tralling slashes.
   * "/" should be used as directory separator.
   * By default, it's "/".
   * Example, "/mysite/", "http://server.com/mysite/".
   *
   * @param string $path http application path
   */
  public static function setHttpRoot($path)
  {
    self::$httpRoot = $path;
  }

  public static function getHttpRoot()
  {
    return self::$httpRoot;
  }

  /**
   * Set file system path to upload directory relative from application root
   * without starting and tralling slashes.
   * "/" should be used as directory separator.
   * By default, it's "media/files".
   *
   * @param string $path path to upload directory
   */
  public static function setUploadDir($path)
  {
    self::$uploadDir = $path;
  }

  public static function getUploadDir($full = FALSE)
  {
    if (!$full)
      return self::$uploadDir;

    return self::getRootDir() . '/' . self::$uploadDir;
  }

  public static function getUploadWebPath($full = FALSE)
  {
    return self::getHttpRoot() . self::$uploadDir;
  }

  /**
   * Set file system path to templates directory relative from application directory
   * without starting and tralling slashes.
   * "/" should be used as directory separator.
   * By default, it's "templates".
   *
   * @param string $path path to templates directory
   */
  public static function setTemplatesDir($path)
  {
    self::$templatesDir = $path;
  }

  public static function getTemplatesDir($full = FALSE)
  {
    if (!$full)
      return self::$templatesDir;

    return self::getRootDir() . '/' . self::getApplicationDir() . '/' . self::$templatesDir;
  }


  public static function addClassDir($path, $classNameSufix = '', $fileNameSufix = '')
  {
    self::$classDirs[] = array(
      'path' => $path,
      'class_name_sufix' => $classNameSufix,
      'file_name_sufix' => $fileNameSufix,
    );
  }

  public static function addConfigDir($path, $fileNameSufix = 'cfg')
  {
    self::$configDirs[] = array(
      'path' => $path,
      'file_name_sufix' => $fileNameSufix,
    );
  }

  public static function getClassDir($className)
  {
    foreach (self::$classDirs as $dirConfig) {
      $preparedClassName = NULL;
      if (!empty($dirConfig['class_name_sufix'])) {
        if (preg_match('/' . $dirConfig['class_name_sufix'] . '$/is', $className)) {
          $preparedClassName = preg_replace('/' . $dirConfig['class_name_sufix'] . '$/is', '', $className);
        }
      } else {
        $preparedClassName = $className;
      }
      if (!empty($preparedClassName)) {
        $fileName = self::getClassFilePrefix($preparedClassName) . '.';
        if (!empty($dirConfig['file_name_sufix']))
          $fileName .= $dirConfig['file_name_sufix'] . '.';
        $fileName .= 'php';

        $filePath = self::getRootDir() . '/' . $dirConfig['path'] . '/' . $fileName;
        if (is_file($filePath))
          return $filePath;
      }
    }
    return NULL;
  }

  public static function getControllerPath($controllerName, $folder = '')
  {
    $fileName = self::getClassFilePrefix($controllerName) . '.php';

    if ($folder)
      $folder .= '/';

    $filePath = self::getRootDir() . '/' . self::getControllersDir() . '/' . $folder . $fileName;
    if (is_file($filePath))
      return $filePath;


    return NULL;
  }

  public static function getConfigDir($configName)
  {
    foreach (self::$configDirs as $dirConfig) {
      $fileName = self::getClassFilePrefix($configName) . '.';
      if (!empty($dirConfig['file_name_sufix']))
        $fileName .= $dirConfig['file_name_sufix'] . '.';
      $fileName .= 'php';
      $filePath = self::getRootDir() . '/' . $dirConfig['path'] . '/' . $fileName;
      if (is_file($filePath))
        return $filePath;
    }
    return NULL;
  }

  public static function setDefaultDirs()
  {
    self::addClassDir(self::getApplicationDir() . '/models', 'Manager', 'manager');
    self::addClassDir(self::getApplicationDir() . '/models', 'Validator', 'validator');
    self::addClassDir(self::getApplicationDir() . '/library/classes', '', 'class');
    self::addClassDir(self::getApplicationDir() . '/library/helpers', 'Helper', 'helper');
    self::addClassDir(self::getApplicationDir() . '/library/services', '', 'service');
    self::addClassDir(self::getApplicationDir() . '/models', 'Model', 'model');
    self::addClassDir(self::getApplicationDir() . '/models/extensions', 'Extension', 'extension');

    self::setControllersDir(self::getApplicationDir() . '/controllers');

    self::addClassDir(self::getCoreDir() . '/classes', '', 'class');
    self::addClassDir(self::getCoreDir() . '/helpers', 'Helper', 'helper');
    self::addClassDir(self::getCoreDir() . '/services', '', 'service');
    self::addClassDir(self::getCoreDir() . '/models', 'Model', 'model');
    self::addClassDir(self::getCoreDir() . '/classes/types', 'Type', 'type');
    self::addClassDir(self::getCoreDir() . '/classes/filter_types', 'FilterType', 'filter.type');

    self::addConfigDir(self::getApplicationDir() . '/config');
  }

  public static function loadClass($className)
  {
    $filePath = self::getClassDir($className);
    if (!empty($filePath))
      include_once($filePath);
    //else
    //   throw new Exception('Couldn\'t find class ' . $className);
  }

  public static function tryToLoadClass($className)
  {
    $filePath = self::getClassDir($className);
    if (!empty($filePath)) {
      include_once($filePath);
      return TRUE;
    } else
      return FALSE;
  }

  public static function loadConfig($configName)
  {
    $filePath = self::getConfigDir($configName);
    if (!empty($filePath))
      include_once($filePath);
    else
      throw new Exception('Couldn\'t find config ' . $configName);
  }

  public static function loadAllConfigInFolder($folder)
  {
    $filepath = self::getRootDir() . '/application/config/' . $folder . '/';

    if (is_dir($filepath)) {
      if ($dh = opendir($filepath)) {
        while (($file = readdir($dh)) !== FALSE) {
          if (filetype($filepath . $file) != 'dir') {
            include_once($filepath . $file);
          }
        }
        closedir($dh);
      }
    }
  }

  /**
   * Метод получения УРЛ строки с возможностью разбора на составляющие
   * @param bool $split - разбивать на контроллер\метод\параметры
   * @return string|array
   */
  public static function getUriPath($split = false) {

      if($split) {

          $path = parse_url($_SERVER['REQUEST_URI'])['path'];
          $exp = explode('/', trim($path, '/'));
          $split = [
              'controller' => !empty($exp[0]) ? $exp[0] : 'index',
              'method' => !empty($exp[1]) ? $exp[1] : 'index',
          ];
          array_shift($exp);
          array_shift($exp);
          $split['params'] = !empty($exp) ? $exp : false;
          return $split;
      }

      if(!empty($_SERVER['REQUEST_URI'])){
          return parse_url($_SERVER['REQUEST_URI'])['path'];
      } else {
          global $uri;
          return $uri;
      }
  }

  public static function getSubdomain() {

      $subdomain = str_replace(['lookmedbook.ru', 'citrus.one', 'lookmedbook.dev'], '', SERVER_NAME);
      $subdomain = trim($subdomain, '.');
      return $subdomain;
  }

    /**
     * @param string $keyname
     * @param mixed  $default
     *
     * @return mixed
     */
    public static function config($keyname, $default = false) {

        static $app_cfg = null;

        if(is_null($app_cfg) AND file_exists(ABS_ROOT.'/application/config/application.cfg.php')){
            $app_cfg = require ABS_ROOT.'/application/config/application.cfg.php';
        }

        if($app_cfg AND array_key_exists($keyname, $app_cfg)){
            return $app_cfg[$keyname];
        }

        return $default;
    }

  private static function getClassFilePrefix($className)
  {
    $fileName = preg_replace('/([a-zA-Z])([A-Z])/s', '\\1_\\2', $className);
    $fileName = strtolower($fileName);
    return $fileName;
  }

  private function stripParametres(&$value)
  {
    if (is_array($value)) {
      foreach ($value as $key => $vv) {
        if (is_array($vv)) {
          $this->stripParametres($value[$key]);
        } else {
          $value[$key] = stripslashes($vv);
        }
      }
    }
  }
}

;
