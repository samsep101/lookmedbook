<?php
    class PhoneNumberViewHelper {

        public static function getView($phone_number)
        {
            $phone_number = preg_replace('/[^0-9]/', '', $phone_number);

            if (strlen($phone_number) == 11)
            {
                preg_match('/^([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})$/', $phone_number, $matches);

                return $matches[1].'('.$matches[2].') '.$matches[3].'-'.$matches[4];
            } else if (strlen($phone_number) == 10) {
                preg_match('/^([0-9]{3})([0-9]{3})([0-9]{4})$/', $phone_number, $matches);
                return '7('.$matches[2].') '.$matches[3].'-'.$matches[4];
            } else {
                return $phone_number;
            }
        }
    }