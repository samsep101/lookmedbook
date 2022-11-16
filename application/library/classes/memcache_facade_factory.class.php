<?php
	class MemcacheFacadeFactory {

		public static function getService()
		{
			$memcache = new MemcacheFacade(SITE_URL);
			if(MEMCACHE_ENABLED)
			{
				$decorator = new MemcacheProfiler();
				$decorator->setDecoratedObject($memcache);
				$memcache = $decorator;
			}

			return $memcache;
		}
	}