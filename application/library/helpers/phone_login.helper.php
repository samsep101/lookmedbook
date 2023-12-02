<?php
    class PhoneLoginHelper
    {
        public static function checkPhone($phone)
        {
            if($length = mb_strlen($phone, 'utf-8') == 12 && $phone[0] == '+' && $phone[1] == '7') {
                return str_replace('+', '', $phone);
            } else {
                if($length = mb_strlen($phone, 'utf-8') == 11 && $phone[0] == '8') {
                    $phone[0] = '7';

                    return $phone;
                } else {
                    return false;
                }
            }
        }
    }