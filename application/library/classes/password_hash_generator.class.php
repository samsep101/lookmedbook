<?php
    class PasswordHashGenerator
    {
        public static function generate($string)
        {
            return sha1($string);
        }
    }