<?php

class SeoHideHelper
{
    public static function begin()
    {
        ob_start();
    }

    public static function end()
    {
        $content = ob_get_clean();
        $hash = md5($content) . time();
        MemcacheAdapter::set(static::getKey($hash), $content);
        echo "<div class=\"js--ajax-content\" data-key=\"$hash\"></div>";
    }

    protected static function getKey($hash)
    {
        return "seohide_$hash";
    }

    public static function getContent($hash)
    {
        return MemcacheAdapter::get(static::getKey($hash));
    }
}
