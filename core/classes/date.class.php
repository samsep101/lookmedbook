<?php
    class Date
    {
        public static function getAge($date, $with_text = FALSE)
        {
            $y_now = date('Y');
            $m_now = date('m');
            $d_now = date('d');

            $birthday_time = strtotime($date);

            $y_birthday = date('Y', $birthday_time);
            $m_birthday = date('m', $birthday_time);
            $d_birthday = date('d', $birthday_time);

            $years = $y_now - $y_birthday;

            if (!(($m_birthday < $m_now) || (($m_birthday == $m_now) && ($d_birthday <= $d_now)))) {
                $years--;
            }

            if ($with_text) {
                return $years . ' ' . self::getAgeWord($years);
            } else {
                return (int)$years;
            }
        }

        public static function getAgeWord($age)
        {
            $text = "лет";
            if ($age % 10 == "1" and $age != "11") $text = "год";
            if ($age % 10 == "2" and $age != "12") $text = "1";
            if ($age % 10 == "3" and $age != "13") $text = "года";
            if ($age % 10 == "4" and $age != "14") $text = "года";

            return $text;
        }
    }