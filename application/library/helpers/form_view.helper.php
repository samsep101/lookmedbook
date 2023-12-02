<?php
    class FormViewHelper
    {
        public static function selectDate($date)
        {
            if (!$date) {
                $d = $m = $y = 0;
            } else {
                $time = strtotime($date);
                $d = (int)date('d', $time);
                $m = (int)date('m', $time);
                $y = date('Y', $time);
            }

            $monthes = array(
                1  => 'Январь',
                2  => 'Февраль',
                3  => 'Март',
                4  => 'Апрель',
                5  => 'Май',
                6  => 'Июнь',
                7  => 'Июль',
                8  => 'Август',
                9  => 'Сентябрь',
                10 => 'Октябрь',
                11 => 'Ноябрь',
                12 => 'Декабрь',
            );

            //число
            $str = '<div class="sel-box">';
            $str .= '<select name="birthday_day" data-placeholder="01" class="chzn-select" style="width:87px;">';
            for ($i = 1; $i <= 31; $i++) {
                $selected = ($i == $d) ? 'selected="selected"' : '';
                if ($i < 10)
                    $i = '0' . $i;

                $str .= '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
            }
            $str .= '</select>';
            $str .= '</div>';

            //месяц
            $str .= '<div class="sel-box">';
            $str .= '<select name="birthday_month" data-placeholder="январь" class="chzn-select" style="width:170px;">';
            //$str .= '<option value="0">-</option>';
            for ($i = 1; $i <= 12; $i++) {
                $selected = ($i == $m) ? 'selected="selected"' : '';
                if ($i < 10)
                    $i = '0' . $i;

                $str .= '<option value="' . $i . '" ' . $selected . '>' . $monthes[(int)$i] . '</option>';
            }
            $str .= '</select>';
            $str .= '</div>';

            //год
            $str .= '<div class="sel-box">';
            $str .= '<select name="birthday_year" data-placeholder="2013" class="chzn-select" style="width:118px;">';
            for ($i = 2012; $i >= 1950; $i--) {
                $selected = ($i == $y) ? 'selected="selected"' : '';
                if ($i < 10)
                    $i = '0' . $i;

                $str .= '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
            }
            $str .= '</select>';
            $str .= '</div>';
            $str .= '<input type="hidden" name="birthday_date" value="'.$date.'" data-error-label-element="select[name=\'birthday_year\']" /><br>';

            return $str;
        }
    }