<?php
    class ButtonPaddingHelper
    {
        public static function getWideButtonSpecialtyPadding($specialty)
        {
            if (strlen($specialty) <= 20)
                return 'style="padding:10px 20px"';
            else
                return 'style="padding:10px 10px"';
        }
    }