<?php

    class TimeType extends Type
    {

        public function getFormValue($val = '')
        {
            $szResult = '<input style="width:5%" type="text" MAXLENGTH="5" name="form[' . $this->fieldName . ']" ';
            if (isset($this->value))
                $szResult .= 'value="' . htmlspecialchars($this->getValue()) . '" ';
            //elseif (!empty($val[$this->fieldName]))
            elseif (!empty($val))
                $szResult .= 'value="' . mb_substr(htmlspecialchars($val),0,5,'UTF-8') . '" ';
            $szResult .= '> (например: 18:30 или 01:15)';

            return $szResult;
        }

        public function getViewValue()
        {
            return htmlspecialchars($this->value);
        }

        public function getSaveValue($value)
        {
            if (strlen($value) == 4)
                return '0' . $value;
            return $value;
        }

    }