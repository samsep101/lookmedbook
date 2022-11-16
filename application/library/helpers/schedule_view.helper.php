<?php

    class ScheduleViewHelper
    {

        static function view($clinic, $showHeader = true)
        {
            $days = self::get_days($clinic);

            $result = $showHeader? '<p class="h-txt2"><strong>График работы</strong></p><p class="h-txt">Часы работы:</p>' : '';

            foreach($days as &$day)
            {
                if($day['status'] == 0)
                {
                    $checking_day  = $day;
                    $day['status'] = 1;
                    $previous_day  = '';
                    $reserv        = '';
                    $combo         = 0;
                    $n_counter     = 0;
                    $counter       = 1;
                    $result .= '<p class="time-line"><span>';
                    $result .= $checking_day['day'];
                    foreach($days as &$compare_day)
                    {
                        if($compare_day['status'] != 1)
                        {
                            if(($checking_day['start'] == $compare_day['start']) && ($checking_day['end'] == $compare_day['end']))
                            {
                                $previous_day          = $compare_day['day'];
                                $compare_day['status'] = 1;
                                if($counter == 1)
                                {
                                    $combo = 0;
                                    if($reserv == 'free') $reserv = $compare_day['day'];
                                }
                                else
                                    $combo = 1;

                                $counter++;
                            }else{
                                if(($previous_day != '') && ($combo == 1))
                                {
                                    if(($reserv != '') && ($reserv != 'free')) $result .= ', ' . $reserv;
                                    $result .= ' - ' . $previous_day;
                                    $previous_day = '';

                                }elseif(($previous_day != '') && ($combo == 0))
                                {
                                    $result .= ', ' . $previous_day;
                                    $previous_day = '';
                                }
                                $n_counter = $counter;
                                $counter   = 1;
                                $reserv    = 'free';
                            }
                        }else{
                            $counter = 1;
                        }
                    }
                    if(($previous_day != '') && ($combo == 1))
                    {
                        if(($reserv != '') && ($reserv != 'free')) $result .= ', ' . $reserv;
                        $result .= ' - ' . $previous_day;
                        $previous_day = '';
                    }
                    elseif(($previous_day != '') && ($combo == 0))
                    {
                        $result .= ', ' . $previous_day;
                    }
                    if(    (($checking_day['start'] != '') &&
                            ($checking_day['start'] != '00:00') &&
                            ($checking_day['end'] != '00:00')) ||

                           (($checking_day['start'] != '') &&
                            ($checking_day['start'] == '00:00') &&
                            ($checking_day['end'] != '00:00')) ||

                          (($checking_day['start'] != '') &&
                           ($checking_day['start'] != '00:00') &&
                           ($checking_day['end'] == '00:00')))

                        $result .= ':</span> ' . $checking_day['start'] . '-' . $checking_day['end'] . '</p>';

                    elseif(($checking_day['start'] == '00:00') && ($checking_day['end'] == '00:00')) $result .= ':</span> <span>Круглосуточно</span>' . '</p>';
                    else
                        $result .= ':</span> <span class="free">Выходной</span>' . '</p>';
                }
            }
            if(strlen($result) == 200) $result = '';

            return $result;
        }

        private static function get_days($clinic)
        {
            $days = array(
                1 => array(
                    'day'    => 'Пн',
                    'start'  => trim($clinic->start_time_monday),
                    'end'    => trim($clinic->end_time_monday),
                    'status' => 0
                ),
                2 => array(
                    'day'    => 'Вт',
                    'start'  => trim($clinic->start_time_tuesday),
                    'end'    => trim($clinic->end_time_tuesday),
                    'status' => 0
                ),
                3 => array(
                    'day'    => 'Ср',
                    'start'  => trim($clinic->start_time_wednesday),
                    'end'    => trim($clinic->end_time_wednesday),
                    'status' => 0
                ),
                4 => array(
                    'day'    => 'Чт',
                    'start'  => trim($clinic->start_time_thursday),
                    'end'    => trim($clinic->end_time_thursday),
                    'status' => 0
                ),
                5 => array(
                    'day'    => 'Пт',
                    'start'  => trim($clinic->start_time_friday),
                    'end'    => trim($clinic->end_time_friday),
                    'status' => 0
                ),
                6 => array(
                    'day'    => 'Сб',
                    'start'  => trim($clinic->start_time_saturday),
                    'end'    => trim($clinic->end_time_saturday),
                    'status' => 0
                ),
                7 => array(
                    'day'    => 'Вс',
                    'start'  => trim($clinic->start_time_sunday),
                    'end'    => trim($clinic->end_time_sunday),
                    'status' => 0
                )
            );

            return $days;
        }

        public static function schedule_in_table($clinic)
        {
            $days = self::get_days($clinic);

            $result = '<table>';

            foreach($days as &$day)
            {
                if($day['status'] == 0)
                {
                    $checking_day  = $day;
                    $day['status'] = 1;
                    $previous_day  = '';
                    $reserv        = '';
                    $combo         = 0;
                    $n_counter     = 0;
                    $counter       = 1;

                    $result .= '<tr><td class="first-cell">';
                    $result .= $checking_day['day'];

                    foreach($days as &$compare_day)
                    {
                        if($compare_day['status'] != 1)
                        {
                            if(($checking_day['start'] == $compare_day['start']) && ($checking_day['end'] == $compare_day['end']))
                            {
                                $previous_day          = $compare_day['day'];
                                $compare_day['status'] = 1;
                                if($counter == 1)
                                {
                                    $combo = 0;
                                    if($reserv == 'free') $reserv = $compare_day['day'];
                                }
                                else
                                    $combo = 1;
                                $counter++;
                            }
                            else
                            {
                                if(($previous_day != '') && ($combo == 1))
                                {
                                    if(($reserv != '') && ($reserv != 'free')) $result .= ', ' . $reserv;
                                    $result .= ' - ' . $previous_day;
                                    $previous_day = '';
                                }
                                else if(($previous_day != '') && ($combo == 0))
                                {
                                    $result .= ', ' . $previous_day;
                                    $previous_day = '';
                                }
                                $n_counter = $counter;
                                $counter   = 1;
                                $reserv    = 'free';
                            }
                        }
                    }
                    if(($previous_day != '') && ($combo == 1))
                    {
                        if(($reserv != '') && ($reserv != 'free')) $result .= ', ' . $reserv;
                        $result .= ' - ' . $previous_day;
                        $previous_day = '';
                    }
                    else if(($previous_day != '') && ($combo == 0))
                    {
                        $result .= ', ' . $previous_day;
                    }
                    if((($checking_day['start'] != '') && ($checking_day['start'] != '00:00') && ($checking_day['end'] != '00:00')) || (($checking_day['start'] != '') && ($checking_day['start'] == '00:00') && ($checking_day['end'] != '00:00')) || (($checking_day['start'] != '') && ($checking_day['start'] != '00:00') && ($checking_day['end'] == '00:00'))
                    ) $result .= ':</td> <td>' . $checking_day['start'] . '-' . $checking_day['end'] . '</td></tr>';
                    else if(($checking_day['start'] == '00:00') && ($checking_day['end'] == '00:00')) $result .= ':</td> <td>Круглосуточно</td>' . '</tr>';
                    else
                        $result .= ':</td> <td class="free">Выходной</td>' . '</tr>';
                }
            }
            $result .= '</table>';

            return $result;
        }

        public static function get_format_days(array $clinicRow, $raw = false) {
            
            $clinicDays = [];
            $before_start = false;
            $before_end = false;
            $group = 0;
            foreach([
                'monday' => 'Пн',
                'tuesday' => 'Вт',
                'wednesday' => 'Ср',
                'thursday' => 'Чт',
                'friday' => 'Пт',
                'saturday' => 'Сб',
                'sunday' => 'Вс',
            ] as $alias => $name){
                
                $current_start = $clinicRow['start_time_'.$alias];
                $current_end = $clinicRow['end_time_'.$alias];

                if(empty($current_start) OR empty($current_end)){
                    continue;
                }

                if(false !== $before_start){
                    if($current_start !== $before_start){
                        $group++;
                    }
                    if($current_end !== $before_end){
                        $group++;
                    }
                }

                $clinicDays[$group][$alias] = [
                    'name' => $name,
                    'start' => $before_start = $current_start,
                    'end' => $before_end = $current_end
                ];
            }

            if($raw){
                return $clinicDays;
            }

            $result = [];
            foreach($clinicDays as $group => $days){
                $first = current($days);
                $last = end($days);
                $separator = ' - ';
                if(count($days) <= 2){
                    $separator = ', ';
                }
                $result[] = implode('', [
                    $first['name'],
                    count($days) == 1 ? '' : $separator,
                    count($days) == 1 ? '' : $last['name'],
                    ':&nbsp;<b>',
                    $first['start'],
                    '-',
                    $last['end'],
                    '</b>'
                ]);
                
            }

            return '<span class="line">'.implode('</span><span class="line">', $result).'</span>';
        }
    }