<?php
    class MobileApiCacheDecorator implements IDecorator
    {
        /**
         * @var ICachedController
         */
        private $decorated_controller;

        /**
         * @var MemcacheFacade
         */
        private $memcache;

        private $if_none_match;

        public function __construct()
        {
            $this->memcache = new MemcacheFacade(SITE_URL);
            $this->getIfNoneMatchHeader();
        }

        public function setDecoratedObject($decorated_object){
           $this->decorated_controller = $decorated_object;
        }

        public function __call($method_name, $params)
        {
            $cached_methods = $this->decorated_controller->getCachedMethods();

            $callback = array(
                $this->decorated_controller,
                $method_name
            );

            if (isset($cached_methods[$method_name]))
            {
                $method_cache_info = $cached_methods[$method_name];

                $hash = $this->generateHash($method_name);

                $last_e_tag = $this->memcache->get($hash);

                if ($this->if_none_match && $last_e_tag == $this->if_none_match)
                {
                    ApiHeader::response304($last_e_tag);
                } else {
                    if (!$last_e_tag)
                    {
                        $e_tag = md5($hash.microtime(true));
                        $this->memcache->set($hash, $e_tag, $this->getMethodTags($method_cache_info['tags']));
                    } else {
                        $e_tag = $last_e_tag;
                    }

                    $this->decorated_controller->setETag($e_tag);
                    return call_user_func_array($callback, $params);
                }
            } else {
                if(is_callable($callback))
                    return call_user_func_array($callback, $params);
            }
        }

        private function getIfNoneMatchHeader()
        {
            $headers = getallheaders();

            if (isset($headers['If-None-Match'])){
                $this->if_none_match = $headers['If-None-Match'];
            }
        }

        private function getMethodTags($tags_info)
        {
            $result = array();

            if ($tags_info)
            {
                foreach($tags_info as $tag_name)
                {
                    if (preg_match_all('/%([^%]+)%/ims', $tag_name, $matches))
                    {
                        foreach($matches[1] as $param_name)
                        {
                            $tag_name = str_replace('%'.$param_name.'%', $_REQUEST[$param_name], $tag_name);
                        }
                    }

                    $result[] = $tag_name;
                }
            }

            return $result;
        }

        private function generateHash($method_name)
        {
            $params = $_REQUEST;
            unset($params['token']);
            unset($params['session']);
            unset($params['time']);
            return md5($method_name.serialize($params));
        }
    }