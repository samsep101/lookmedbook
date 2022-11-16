<?php

class MemcacheHtmlCache implements IHtmlCache
{
  /**
   * @var MemcacheFacade|MemcacheProfiler
   */
  protected $memcache_api = null;

  private $cache_id;
  private $tags = array();

  private $enabled = 1;

  public function __construct()
  {
    $this->memcache_api = MemcacheFacadeFactory::getService();
  }

  public function enable()
  {
    $this->enabled = 1;
  }

  public function disable()
  {
    $this->enabled = 0;
  }

  public function start($cache_id, $groups = array())
  {
    if (HTML_CACHE_ENABLE == 0)
      return false;

    if (!$this->enabled)
      return false;

    $tags = array();

    if (is_string($groups)) {
      $tags[] = $groups;
    } else {
      $tags = $groups;
    }

    $tags[] = 'site_cache';

    $this->cache_id = $cache_id;
    $this->tags = $tags;

    if ($data = $this->memcache_api->get($cache_id)) {
      echo $data;
      return true;
    } else {
      ob_start();
      return false;
    }
  }

  public function end()
  {
    if (HTML_CACHE_ENABLE == 0)
      return false;

    $content = ob_get_contents();
    if ($this->enabled) {
      $this->memcache_api->set($this->cache_id, $content, $this->tags);
    }
    ob_end_flush();
  }

  /**
   * @param $cache_id
   *
   * @return bool
   */
  public function delete($cache_id)
  {
    $this->memcache_api->delete($cache_id);
  }

  /**
   * @param $tag
   *
   * @return bool
   */
  public function deleteGroup($tag)
  {
    $this->memcache_api->deleteGroup($tag);
  }

  public function clearAll()
  {
    $this->memcache_api->deleteGroup('site_cache');
  }
}