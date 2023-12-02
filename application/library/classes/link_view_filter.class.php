<?php
    class LinkViewFilter extends ViewFilter
    {
        public function filter($html)
        {
            if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] && isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] && (strpos($_SERVER['REQUEST_URI'], 'admin/') || strpos($_SERVER['REQUEST_URI'], 'registry/')  || strpos($_SERVER['REQUEST_URI'], 'uslugi/')))
            {
                return $html;
                /**
                 * WTF???
                 */
                //$html = str_replace('href="http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'"', 'href="javascript:void(0)"', $html);
                //$html = str_replace('href="'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'"', 'href="javascript:void(0)"', $html);
                //$html = str_replace('href="'.$_SERVER['REQUEST_URI'].'"', 'href="javascript:void(0)"', $html);
            }

            $html = SeoLinkViewHelper::convertLinks($html);

            return $html;
        }
    }