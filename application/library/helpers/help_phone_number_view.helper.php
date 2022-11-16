<?php
    class HelpPhoneNumberViewHelper
    {
        public static function getPhoneNumber($city = null)
        {
            if (empty(SITE_PHONE)) {
                return '';
            }
            return '+7(' . SITE_PHONE_CODE . ') ' . SITE_PHONE;
        }

        public static function getPhoneNumberLink($city = null)
        {
            if (empty(SITE_PHONE)) {
                return '';
            }
            return sprintf('<a href="tel:+7(%s)%s">%s</a>', SITE_PHONE_CODE, SITE_PHONE, static::getPhoneNumber($city));
        }

        public static function renderPhoneBlock($template, $city = null)
        {
            $phone = static::getPhoneNumber($city);

            if (!$phone) {
                return '';
            }

            $phoneLink = static::getPhoneNumberLink($city);

            return strtr($template, ['{%phone%}' => $phone, '{%phoneLink%}' => $phoneLink]);
        }
    }