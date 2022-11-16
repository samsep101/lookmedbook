<?php
    class MemcacheMemoryCacheAdapter implements IMemoryCache
    {
        /**
         * @var Memcache
         */
        protected $memcache;

        public function __construct()
        {
            $this->memcache = Register::get('memcache');
        }

        public function setValue($key, $value, $expiration_time, $group_name = null)
        {
            /**
             * @var Profiler $profiler
             */
            $profiler = ProfilerFactory::getProfiler();
            $profiler->startTime('memcache');

            if (!$expiration_time)
            {
                $expiration_time = time() + 24*60*60;
            }

            $data = array(
                'expiration_time' => $expiration_time,
                'group_name' => $group_name,
                'group_version' => $this->getGroupVersion($group_name),
                'result' => $value
            );
            $this->memcache->set($key, $data, false, 0);

            $profiler->stopTime('memcache');
        }

        public function getValue($key)
        {
            /**
             * @var Profiler $profiler
             */
            $profiler = ProfilerFactory::getProfiler();
            $profiler->startTime('memcache');

            $data = $this->memcache->get($key);

            if ($data)
            {
                if ($data['expiration_time']  < time())
                {
                    $this->clearValue($key);
                    return false;
                }

                if($data['group_name'])
                {
                    $group_version = $this->getGroupVersion($data['group_name']);

                    if ($group_version != $data['group_version'])
                    {
                        return false;
                    }
                }

                $profiler->stopTime('memcache');
                return $data['result'];
            } else {
                $profiler->stopTime('memcache');
                return false;
            }
        }

        public function clearValue($key)
        {
            $this->memcache->delete($key);
        }

        private function getGroupVersion($group_name)
        {
            $version_key = $this->getGroupKeyByGroupName($group_name);

            $version_number = $this->memcache->get($version_key);

            if (!$version_number)
            {
                $version_number = 1;
                $this->memcache->add($version_key, $version_number, false, 0);
            }

            return $version_number;
        }

        private function getGroupKeyByGroupName($group_name)
        {
            return 'version_number_'.$group_name;
        }

        public function clearAllCache()
        {
            $this->memcache->flush();
        }

        public function clearGroupCache($group_name)
        {
            $this->memcache->increment($this->getGroupKeyByGroupName($group_name));
        }

    }