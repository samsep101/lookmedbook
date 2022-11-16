<?php
    /**
     * Pager
     *
     */
    class DateViewHelper
    {

        public static function date($value, $type = 'full')
        {
            if (!$value)
                return false;
            if (!is_numeric($value))
                $value = strtotime($value);

            $aMonth = array(
                1    => 'января',
                2    => 'февраля',
                3    => 'марта',
                4    => 'апреля',
                5    => 'мая',
                6    => 'июня',
                7    => 'июля',
                8    => 'августа',
                9    => 'сентября',
                10   => 'октября',
                11   => 'ноября',
                12   => 'декабря',
            );

            $aWeekDay = array(
                'Monday'    => 'понедельник',
                'Tuesday'    => 'вторник',
                'Wednesday'    => 'среда',
                'Thursday'    => 'четверг',
                'Friday'    => 'пятница',
                'Saturday'    => 'суббота',
                'Sunday'    => 'воскресенье'
            );
            $month = intval(date('m', $value));
            switch ($type) {
                case 'number':
                    return date('d.m.y', $value);
                case 'dd.mm.YYYY':
                    return date('d.m.Y', $value);
                case 'day':
                    return mktime(0, 0, 0, date('m', $value), date('d', $value), date('Y', $value));
                case 'only_day' :
                    return date('d', $value);
                case 'only_month' :
                    $m = (mb_strpos('0',date('m', $value)) == 0) ? mb_substr(date('m', $value),-1) : date('m', $value);
                    return $aMonth[$m];
                case 'only_year' :
                    return date('Y', $value);
                case 'time' :
                    return date('H:i', $value);
                case 'day_and_month':
                    return date('d', $value) . ' ' . $aMonth[$month];
                case 'day_and_month_and_week_day':
                    return date('d', $value) . ' ' . $aMonth[$month].' ('.$aWeekDay[date('l', $value)].')';
                case 'full':
                    return date('d', $value) . ' ' . $aMonth[$month] . ' ' . date('Y', $value);
                case 'with_week_day':
                    return date('d', $value) . ' ' . $aMonth[$month] . ' ' . date('Y', $value). 'г. ' . $aWeekDay[date('l', $value)];
                case 'date_and_time' :
                    return date('d.m.Y', $value).' '.date('H:i', $value);
                case 'dd-mm-yyyy' :
                    return date('d-m-Y', $value);
                default:
                    return date('d', $value) . ' ' . $aMonth[$month];
            }
        }

        //return date format for insert in database
        public static function setBaseDateFormat($day, $month, $year)
        {
            $date = $year . '-' . $month . '-' . $day;
            return $date;
        }

        //return day, monthm yers from database
        public static function getDateFormat($date)
        {
            $date_array = explode("-", $date);
            return $date_array;
        }

        public static function message_date($value)
        {
            $day = DateViewHelper::date($value, 'only_day');
            $month = DateViewHelper::date($value, 'only_month');
            $time = DateViewHelper::date($value, 'time');

            if ($day == date('d'))
                $day_month = 'Сегодня';
            else if (date('d') - $day == 1)
                $day_month = 'Вчера';
            else
                $day_month = $day . ' ' . $month;

            $dt = $day_month . ', ' . $time;
            return $dt;
        }

        public static function getMonthName($date)
        {
            $month = array(
                1    => 'января',
                2    => 'февраля',
                3    => 'марта',
                4    => 'апреля',
                5    => 'мая',
                6    => 'июня',
                7    => 'июля',
                8    => 'августа',
                9    => 'сентября',
                10   => 'октября',
                11   => 'ноября',
                12   => 'декабря'
            );

            $result = $month[date('n', $date)];
            return $result;
        }

        public static function getActualizationDate()
        {
            $date1 = strtotime("1970-01-01 00:00:00");
            $date2 = strtotime(date('Y-m-d H:i:s'));
            $diff = abs($date1 - $date2)*1000;
            return $diff;
        }
    }