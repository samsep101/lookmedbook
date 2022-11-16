<?php
	class DateHelper
	{
		public static function getDayOfWeekNameByDate($timestamp)
		{
			return strtolower(date('l', $timestamp));
		}

		public static function getHoursByTime($time)
		{
			if (!self::isTime($time))
				return NULL;

			preg_match('/^([0-9]{1,2}):[0-9]{2}(?::[0-9]{2})?$/ims', $time, $matches);

			$hours = $matches[1];
			if ($hours < 10)
				$hours = '0'.(int)$hours;

			return $hours;
		}

		public static function getMinutesByTime($time)
		{
			if (!self::isTime($time))
				return NULL;

			preg_match('/^[0-9]{1,2}:([0-9]{2})(?::[0-9]{2})?$/ims', $time, $matches);

			$minutes = $matches[1];

			return $minutes;
		}

		public static function isTime($time)
		{
			return preg_match('/^[0-9]{1,2}:[0-9]{2}(?::[0-9]{2})?$/ims', $time);
		}

		public static function now()
		{
			return date('Y-m-d H:i:s');
		}

		public static function getWeekDayName($day_number)
		{
			$days = self::getWeekDaysNames();
			return $days[$day_number];
		}

		public static function getWeekDaysNames()
		{
			return array(
				'sunday',
				'monday',
				'tuesday',
				'wednesday',
				'thursday',
				'friday',
				'saturday'
			);
		}

		public static function getDayNumberByShortRuName($ru_name)
		{
			$number = null;

			switch($ru_name)
			{
				case 'пн':
					$number = 1;
					break;
				case 'вт':
					$number = 2;
					break;
				case 'ср':
					$number = 3;
					break;
				case 'чт':
					$number = 4;
					break;
				case 'пт':
					$number = 5;
					break;
				case 'сб':
					$number = 6;
					break;
				case 'вс':
					$number = 0;
					break;
			}

			return $number;
		}

        public static function getDayNumberByRuName($ru_name)
        {
            switch($ru_name)
            {
                case 'Понедельник':
                    return 1;
                case 'Вторник':
                    return 2;
                case 'Среда':
                    return 3;
                case 'Четверг':
                    return 4;
                case 'Пятница':
                    return 5;
                case 'Суббота':
                    return 6;
                case 'Воскресенье':
                    return 0;
            }

			return null;
        }

		public static function toMysqlDateFormat($date)
		{
			if (preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/ims', $date)){
				return $date;
			}

			$ts = strtotime($date);
			$date = date('Y-m-d', $ts);

			return $date;
		}

		public static function format($date, $format)
		{
			$ts = strtotime($date);

			$date = date($format, $ts);

			return $date;
		}

        public static function changeFormat($str, $separator = '.')
        {
            if(!$str)
            {
                return null;
            }

            $elements = explode($separator, $str);

            if(count($elements) == 3)
            {
                return $elements[2]. '-' .$elements[1] .'-' .$elements[0];
            }
            else
            {
                return null;
            }
        }
	}
