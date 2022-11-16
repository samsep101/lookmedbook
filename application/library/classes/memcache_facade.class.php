<?php
	class MemcacheFacade
	{
		/**
		 * @var Memcache
		 */
		private $memcache;

		private $prefix;

		private $data_cache = array();

		public function __construct($prefix = '')
		{
			$this->memcache = Register::get('memcache');
			$this->prefix = $prefix;
		}

		public function set($key, $value, $tags = array(), $expire = 0)
		{
			$tags_versions = array();

			if($tags)
			{
				foreach($tags as $tag)
				{
					$tags_versions[$tag] = $this->getTagVersion($tag);
				}
			}

			$data = array('data' => $value, 'expire' => strtotime($expire), 'tags' => $tags_versions);

			return $this->memcache->set($this->getKey($key), $data, 0, 0);
		}

		public function get($key)
		{
			$key = $this->getKey($key);
			$data = $this->getMemcacheData($key);

			if($data)
			{
				if(isset($data['tags']) && $this->checkActualTagData($data['tags']) && (!$data['expire'] || ($data['expire'] < time())))
				{
					return $data['data'];
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}

		private function getMemcacheData($key)
		{
			if(isset($this->data_cache[$key]))
			{
				$data = $this->data_cache[$key];
			}
			else
			{
				$data = $this->memcache->get($key);
				$this->data_cache[$key] = $data;
			}

			return $data;
		}

		public function update($key, $value)
		{
			$key = $this->getKey($key);
			$data = $this->getMemcacheData($key);
			if(!$data)
				return;

			$data['data'] = $value;
			$this->memcache->set($key, $data, 0, 0);
		}

		public function delete($key)
		{
			$this->memcache->delete($this->getKey($key));
		}

		public function deleteGroup($tag)
		{
			$key = $this->getKey('tag:version:' . $tag);
			if($this->memcache->get($key))
			{
				$this->memcache->increment($key, 2);
			}
			else
			{
				$this->memcache->set($key, 2);
			}
		}

		private function checkActualTagData($tags)
		{
			if($tags)
			{
				foreach($tags as $tag_name => $tag_version)
				{
					if($tag_version != $this->getTagVersion($tag_name))
					{
						return false;
					}
				}
			}

			return true;
		}

		private function getTagVersion($tag_name)
		{
			$key = 'tag:version:' . $tag_name;
			$data = $this->memcache->get($this->getKey($key));

			return $data ? $data : 1;
		}

		private function getKey($key)
		{
			return $this->prefix . ':' . $key;
		}
	}