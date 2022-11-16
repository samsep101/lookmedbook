<?php

    class TimeSliderType extends Type
    {

        public function getFormValue($val = '')
        {
            $szResult = '<div style="padding-top:30px; padding-bottom:30px; width:80%">';
            //$szResult.= '<input style="width:50%" type="text" name="form['.$this->fieldName.']" ';

            $szResult .= '<div class="layout-slider"><input id="timeSlider" type="slider" name="form[' . $this->fieldName . ']" ';

            if (isset($this->value))
                $szResult .= 'value="' . htmlspecialchars($this->getValue()) . '" ';
            elseif (!empty($val[$this->fieldName]))
                $szResult .= 'value="' . htmlspecialchars($val[$this->fieldName]) . '" '; else {
                $hh = date('H');
                $szResult .= ' value="' . ($hh * 60) . ';' . (($hh + 1) * 60) . '" ';
            }

            $szResult .= ' /></div>';
            $szResult .= '<script type="text/javascript" charset="utf-8">';

            $szResult .= 'jQuery("#timeSlider").slider({ from: 360, to: 1260, step: 15, skin: "round_plastic", dimension: \'\', scale: [\'6:00\',\'7:00\',\'8:00\', \'9:00\', \'10:00\', \'11:00\', \'12:00\', \'13:00\', \'14:00\', \'15:00\', \'16:00\', \'17:00\', \'18:00\', \'19:00\', \'20:00\', \'21:00\'], limits: false, calculate: function( value ){
	        var hours = Math.floor( value / 60 );
	        var mins = ( value - hours*60 );
	        return (hours < 10 ? "0"+hours : hours) + ":" + ( mins == 0 ? "00" : mins );
	      }})
	    ';
            $szResult .= '</script>';
            $szResult .= '</div>';

            return $szResult;
        }

        public function getViewValue()
        {
            $at = htmlspecialchars(substr($this->value, 0, strpos($this->value, ';')));
            $fn = htmlspecialchars(substr($this->value, strpos($this->value, ';') + 1));
            $at1 = floor($at / 60);
            $at2 = $at - $at1 * 60;
            if (strlen('' . $at2) == 1) {
                $at2 = '0' . $at2;
            }
            $at = $at1 . ':' . $at2;

            $fn1 = floor($fn / 60);
            $fn2 = $fn - $fn1 * 60;
            if (strlen($fn2) == 1) {
                $fn2 = '0' . $fn2;
            }
            $fn = $fn1 . ':' . $fn2;

            return 'c ' . $at . ' по ' . $fn;
        }


    }