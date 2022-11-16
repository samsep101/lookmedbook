<?php
	class HtmlCacheFactory
	{
		public static function getInstance()
		{
			if(HTML_CACHE_TYPE == 'Cache_Lite')
			{
				$cache = new CacheLiteHtmlCacheAdapter();
			}
			elseif(HTML_CACHE_TYPE == 'Memcache')
			{
				$cache = new MemcacheHtmlCache();
			} else {
				throw new Exception('Incorrect param');
			}

			return $cache;
		}
	}