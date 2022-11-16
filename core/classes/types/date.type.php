<?php

class DateType extends Type
{
    var $aMonth;
    var $bEmpty = FALSE;

    public function getSaveValue($date)
    {
        /*
       if (is_numeric($date))
           return $date;
       */

        if (preg_match('|(\d{1,2})\.(\d{1,2})\.(\d{1,4}) (\d{1,2})\:(\d{1,2})|', $date, $aD)) {

            $d = $aD[1];
            $m = $aD[2];
            $y = $aD[3];
            $h = $aD[4];
            $min = $aD[5];
            if (strlen($y) == 2)
                $y = '20' . $y;

            return $y . '-' . $m . '-' . $d . ' ' . $h . ':' . $min;
        }
        if (preg_match('|(\d{1,2})\-(\d{1,2})\-(\d{1,4}) (\d{1,2})\:(\d{1,2})|', $date, $aD)) {
        	$d = $aD[1];
            $m = $aD[2];
            $y = $aD[3];
            $h = $aD[4];
            $min = $aD[5];
            if (strlen($y) == 2)
                $y = '20' . $y;
            return $y . '-' . $m . '-' . $d . ' ' . $h . ':' . $min;
        }

        if (preg_match('|(\d{1,2})\.(\d{1,2})\.(\d{1,4})|', $date, $aD)) {
            $d = $aD[1];
            $m = $aD[2];
            $y = $aD[3];
            if (strlen($y) == 2)
                $y = '20' . $y;
            return $y . '-' . $m . '-' . $d;
        }
        if (preg_match('|(\d{1,2})\-(\d{1,2})\-(\d{1,4})|', $date, $aD)) {
        	$d = $aD[1];
            $m = $aD[2];
            $y = $aD[3];
            if (strlen($y) == 2)
                $y = '20' . $y;
            return $y . '-' . $m . '-' . $d;
        }
    }

    public function getFormValue($val = '')
    {
        if(isset($this->fieldInfo['Format']) && $this->fieldInfo['Format']){
            $format = $this->fieldInfo['Format'];
            if ($val) {
                $value = date($format.' H:i', strtotime($val));
            } else {
                $value = date('d-m-Y H:i');
            }
            $showTime = !empty($this->fieldInfo['show_time']);
            if (!$showTime)
                if ($value)
                    $value = date($format, strtotime($value));
                else
                    if ($value)
                        $value = date($format.' H:i', strtotime($value));

        } else {
            if ($val) {
                $value = date('d.m.Y H:i', strtotime($val));
            } else {
                $value = date('d-m-Y H:i');
            }

			if ($this->fieldName == 'visit_start_time')
                $format = null;
			else
                $format = 'd.m.Y';
            $showTime = !empty($this->fieldInfo['show_time']);
            if (!$showTime)
                if ($value)
                    $value = date('d-m-Y', strtotime($value));
                else
                    if ($value)
                        $value = date('d-m-Y H:i', strtotime($value));
        }

        if ($value == '01-01-1970')
            $value = '';
//            $value = date('d-m-Y H:i');

        $result = '<input type="text" name="form[' . $this->getFieldName() . ']" value="' . $value . '" id="' . $this->getFieldName() . '">';
        $result .= '<input type="button" id="' . $this->getFieldName() . '_picker" value="Выбрать дату">';
        $showTimeJs = $showTime ? 'showsTime: true, ifFormat:"%d.%m.%Y %H:%M", daFormat:"%d.%m.%Y %H:%M",' : '';
        if($showTime)
            $formatJs = $format ? 'ifFormat:"%d-%m-%Y %H:%M", daFormat:"%d-%m-%Y %H:%M",' : '';
        else
            $formatJs = $format ? 'ifFormat:"%d-%m-%Y", daFormat:"%d-%m-%Y",' : '';

        $result .= <<<EOD
		<script>
			Calendar.setup(
				{
					{$showTimeJs}
					{$formatJs}
					inputField: '{$this->getFieldName()}',
					button: '{$this->getFieldName()}_picker',
					date: '{$value}'
				}
			);
			Calendar.setup(
				{
					{$showTimeJs}
					{$formatJs}
					inputField: '{$this->getFieldName()}',
					button: '{$this->getFieldName()}',
					date: '{$value}',
					eventName: 'click'
				}
			);

		</script>
EOD;

        return $result;
    }


    public function getValueArray()
    {
        $aDate = explode('-', $this->value);
        return array(
            'year'  => $aDate[0],
            'month' => $aDate[1],
            'day'   => intval($aDate[2]),
        );
    }

    public function getViewValue($value)
    {
        if ($value == '0000-00-00 00:00:00' || $value == '0000-00-00' || !$value) {
            return '';
        } else {
            if(isset($this->fieldInfo['Format']) && $this->fieldInfo['Format']){
                if (empty($this->fieldInfo['show_time']))
                    return date($this->fieldInfo['Format'], strtotime($value));
                else
                    return date($this->fieldInfo['Format'].' H:i', strtotime($value));
            } else{
                if (empty($this->fieldInfo['show_time']))
                    return date('d.m.Y', strtotime($value));
                else
                    return date('d.m.Y H:i', strtotime($value));
            }
        }
    }
}