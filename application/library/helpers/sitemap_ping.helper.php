<?php
    class SitemapPingHelper
    {
        public static function httpGoogle() {
            return CurlRequestSender::get('http://google.com/webmasters/sitemaps/ping?sitemap='.SITE_URL.'sitemap.xml');
        }

        public static function httpYandex() {
            return CurlRequestSender::get('http://webmaster.yandex.ru/wmconsole/sitemap_list.xml?host='.SITE_URL.'sitemap.xml');
        }

        public static function httpBing() {
            return CurlRequestSender::get('http://www.bing.com/webmaster/ping.aspx?siteMap='.SITE_URL.'sitemap.xml');
        }
    }