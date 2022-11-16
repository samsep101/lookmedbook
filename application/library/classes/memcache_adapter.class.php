<?php
    class MemcacheAdapter
    {
        public static function get($name)
        {
            if (!MEMCACHE_ENABLED)
                return false;

            $memcache = Register::get('memcache');

            return $memcache->get($name);
        }

        public static function set($name, $value)
        {
            /**
             * @var Memcache $memcache
             */
            if (MEMCACHE_ENABLED)
            {
                $memcache = Register::get('memcache');
                $memcache->set($name, $value, false, 600);
            }
        }

		public static function delete($key)
		{
			/**
			 * @var Memcache $memcache
			 */
			$memcache = Register::get('memcache');
			$memcache->delete($key);
		}

        public static function clear()
        {
            if (MEMCACHE_ENABLED)
            {
                $memcache = Register::get('memcache');
                $memcache->flush();
            }
        }

        public static function flush()
        {
            if (MEMCACHE_ENABLED)
            {
                $memcache = Register::get('memcache');
                $memcache->flush();
            }
        }

        public static function remove($name)
        {
            if(MEMCACHE_ENABLED)
            {
                $memcache = Register::get('memcache');
                $memcache->delete($name);
            }
        }
    }