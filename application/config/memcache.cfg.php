<?php
    $memcache = new Memcache();
    $memcache->connect('memcached',11211);

    define('MEMCACHE_ENABLED', true);
    define('MEMCACHE_MODEL_MANAGER', 1);

    Register::add('memcache', $memcache);