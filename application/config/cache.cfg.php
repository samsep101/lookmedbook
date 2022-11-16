<?php
require_once(ABS_ROOT . '/application/library/third_party/Cache/Lite.php');
require_once(ABS_ROOT . '/application/library/third_party/Cache/Lite/Output.php');

define('HTML_CACHE_ENABLE', 0);
define('HTML_CACHE_TYPE', 'Memcache');
//define('HTML_CACHE_TYPE', 'Cache_Lite');

