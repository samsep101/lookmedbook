<?php
	class CacheLiteHtmlCacheAdapter implements IHtmlCache
	{
		/**
		 * @var Cache_Lite_Output
		 */
		private $cache_lite;

		private $enabled = 1;

		public function __construct()
		{
			$options = array(
				'cacheDir'        => './cache/',
				'caching'         => (bool)$this->enabled,
				'writeControl'    => true,
				'readControl'     => true,
				'readControlType' => 'md5'
			);

			$cache = new Cache_Lite_Output($options);
			$cache->setLifeTime(86400);

			$this->cache_lite = $cache;
		}

		public function enable()
		{
			$this->enabled = 1;
		}

		public function disable()
		{
			$this->enabled = 0;
		}


		/**
		 * @param $cache_id
		 * @param $tags
		 *
		 * @return bool
		 */
		public function start($cache_id, $tags = array())
		{
			return $this->cache_lite->start($cache_id, $tags);
		}

		/**
		 * @param $cache_id
		 *
		 * @return bool
		 * @throws Exception
		 */
		public function delete($cache_id)
		{
			throw new Exception('Not implemented');
		}

		/**
		 * @param $tag
		 *
		 * @return bool
		 */
		public function deleteGroup($tag)
		{
			return $this->cache_lite->clean($tag);
		}

		/**
		 * @return void
		 */
		public function end()
		{
			$this->cache_lite->end();
		}

		public function clearAll()
		{
			$this->cache_lite->clean();
		}
	}