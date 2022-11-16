<?php
    interface IMemoryCache {

        public function setValue($key, $value, $expiration_time, $group_name = null);
        public function getValue($key);

        public function clearAllCache();
        public function clearValue($key);
        public function clearGroupCache($group_name);
    }