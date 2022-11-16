<?php

class Visit_time_selectType extends Type
{

    public function getFormValue($val = '', $model = null)
    {
        $result = '';

        $visit_time_from = strtotime($model->schedule->dt_start);

        $day = DateHelper::getDayOfWeekNameByDate($visit_time_from);
        $start_time = 'start_time_'.$day;
        $end_time = 'end_time_'.$day;

        if (($model->clinic->$start_time)&&($model->clinic->$end_time)) {
            $visit_time_from = strtotime($model->clinic->$start_time);
            $visit_time_to = strtotime($model->clinic->$end_time);

            $diff = $visit_time_to - $visit_time_from;

            $slots_count = $diff/(30*60);

            $result = '<select name="form[' . $this->fieldName . ']">';

            $result .= '<option value=""></option>' . "\n";

            for ($i=0; $i <  $slots_count; $i++)
            {
                $time = date('H:i', $visit_time_from + $i*30*60);
                $selected = ($time == $val) ? 'selected="selected"' : '';
                $result .= '<option value="'.$time.'" '.$selected.' >'.$time.'</option>';
            }

            $result .= '</select>';
        }
        return $result;
    }
}