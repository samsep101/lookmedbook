<?php
    class UrlViewHelper {
        public static function getShortView($url)
        {
            $url = str_replace(SITE_SCHEME . '://', '', $url);
            $url = str_replace('www.','' , $url);
            $url = preg_replace('/\/$/', '', $url);

            return $url;
        }

        public static function getLinkView($url)
        {
            if (!preg_match('/^' . SITE_SCHEME . '/', $url))
            {
                return SITE_SCHEME . '://'.$url;
            } else {
                return $url;
            }
        }
    }