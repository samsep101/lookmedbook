<?php

class RedirectManager
{

  public static function redirect($url)
  {
    self::redirect301($url);
  }

  public static function redirect301($url)
  {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $url);
    exit();
  }
}