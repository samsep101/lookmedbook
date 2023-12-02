<?php
    class SitemapLink
    {
        public $url;
        public $changefreq = 'weekly';
        public $priority = '0.9';

        public static function getLastmod($filename){
            $lastmod_date = null;
            if (file_exists($filename))
                $lastmod_date =  date(DATE_ATOM, filemtime($filename));
            else
                $lastmod_date = date(DATE_ATOM);

            return $lastmod_date;
        }
    }
