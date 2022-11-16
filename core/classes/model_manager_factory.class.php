<?php

class ModelManagerFactory
{
  private static $register;

  /**
   * @param string $manager_name
   * @return ModelManager|ModelManagerCacheDecorator
   */
  public static function getByName($manager_name)
  {
    $manager_name = str_replace('_', ' ', $manager_name);
    $manager_name = ucwords($manager_name);
    $manager_name = str_replace(' ', '', $manager_name);

    if (isset(self::$register[$manager_name]))
      return self::$register[$manager_name];


    $manager_class_name = $manager_name . 'Manager';
    if ($manager_name != 'Model' and (class_exists($manager_class_name, FALSE) || Application::tryToLoadClass($manager_class_name))) {
      $manager_class_name = $manager_name . 'Manager';

      $manager = new $manager_class_name();

      if (MEMCACHE_ENABLED) {
        $decorator = new ModelManagerCacheDecorator($manager);
        $manager = $decorator;
      }

      self::$register[$manager_name] = $manager;

      return self::$register[$manager_name];
    }

    return FALSE;
  }


  public static function getManagerByModel(DynamicModel $manager_name)
  {
    $class_name = get_class($manager_name);

    if (preg_match('/^(.+)Model$/', $class_name, $matches)) {
      return self::getByName($matches[1]);
    }
  }

  /**
   * @return ModelManager
   */
  public static function getManagerOrDefaultManager($manager_name)
  {
    $manager = self::getByName($manager_name);

    /*
    if (!$manager) {
        $manager = new ModelManager($manager_name);
    }
    */

    return $manager;
  }
}
