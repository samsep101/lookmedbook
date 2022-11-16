<?php

    class PasswordType extends Type
    {

        public function getFormValue($val = '')
        {
            $szResult = '<input style="width:50%" type="password" name="form[' . $this->fieldName . ']" ';
            if (isset($this->value))
                $szResult .= 'value="' . htmlspecialchars($val) . '" ';
            elseif (!empty($val[$this->fieldName]))
                $szResult .= 'value="' . htmlspecialchars($val) . '" ';
            $szResult .= '>';
            if (!empty($this->fieldInfo['label']))
                $szResult .= '<span class="label">* ' . $this->fieldInfo['label'] . '</span>';

            return $szResult;
        }

        public function getViewValue()
        {
            return htmlspecialchars($this->value);
        }

        public function getSaveValue($value)
        {
            //сейчас - примитивно, проверка под длинне
            if (mb_strlen($value, 'utf-8') == 40) {
                return $value;
            } else {
                return sha1($value);
            }
        }

    }