<?php
    class RedisMemoryCacheAdapter implements IMemoryCache
    {
        /**
         * @var Rediska
         */
        private $redis;

        public function __construct()
        {
            $this->redis = Register::get('redis');
        }

        public function setValue($key, $value, $expiration_time, $group_name = null)
        {
            /**
             * @var Profiler $profiler
             */
            $profiler = Profiler::getInstance();
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
            $this->redis->set($key, $data);

            $profiler->stopTime('memcache');
        }

        public function getValue($key)
        {
            /**
             * @var Profiler $profiler
             */
            $profiler = Profiler::getInstance();
            $profiler->startTime('memcache');

            $data = $this->redis->get($key);

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

        public function clearAllCache()
        {
            $this->redis->flushDb();
        }

        public function clearValue($key)
        {
            $this->redis->delete($key);
        }

        public function clearGroupCache($group_name)
        {
            $this->redis->increment($this->getGroupKeyByGroupName($group_name));
        }

        private function getGroupVersion($group_name)
        {
            $version_key = $this->getGroupKeyByGroupName($group_name);

            $version_number = $this->redis->get($version_key);

            if (!$version_number)
            {
                $version_number = 1;
                $this->redis->set($version_key, $version_number);
            }

            return $version_number;
        }

        private function getGroupKeyByGroupName($group_name)
        {
            return 'version_number_'.$group_name;
        }
    }