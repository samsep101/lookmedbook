<?php

    class SimpleTextType extends Type
    {

        public function getFormValue($val = '')
        {
            $szResult = '<span id="' . $this->fieldName . '_text">';
            if (isset($this->value))
                $szResult .= '<strong>' . htmlspecialchars($this->getValue()) . '</strong>';
            elseif (!empty($val[$this->fieldName]))
                $szResult .= '0';
            $szResult .= '</span> <input type="hidden" id="' . $this->fieldName . '" name = "form[' . $this->fieldName . ']" value="' . htmlspecialchars($this->getValue()) . '" />';
            return $szResult;
        }

        public function getViewValue()
        {
            return htmlspecialchars($this->value);
        }

    }