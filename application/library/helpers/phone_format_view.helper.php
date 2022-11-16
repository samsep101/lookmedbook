<?php
    class PhoneFormatViewHelper
    {
        public static function view($number)
        {
            $number = preg_replace('/[^0-9\+]/', '', $number);
            $number = preg_replace('/\+?((?:7)|(?:375))(.+)([0-9]{3})([0-9]{2})([0-9]{2})/', '+$1-$2-$3-$4-$5', $number);

            return $number;
        }

        public static function view_with_brackets($number)
        {
            $number = preg_replace('/[^0-9\+]/', '', $number);
            $number = preg_replace('/\+?((?:7)|(?:375))(.+)([0-9]{3})([0-9]{2})([0-9]{2})/', '+$1 ($2) $3-$4-$5', $number);

            return $number;
        }
    }