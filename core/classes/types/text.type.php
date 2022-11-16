<?php

    class TextType extends Type
    {

        public function getViewValue($val)
        {
            if ($val) {
                $result = mb_substr(htmlspecialchars($val), 0, 50, 'UTF-8');
                if (mb_strlen($val) > mb_strlen($result)) {
                    $result .= '...';
                }
                return $result;
            }
            return '';
        }

        public function getFormValue($val = '')
        {
            $result = '<textarea ';
            if (isset($this->fieldInfo['style'])) {
                $result .= ' style="' . $this->fieldInfo['style'] . '" ';
            } else {
                $result .= ' rows=12 ';
            }
            $result .= 'name="form[' . $this->fieldName . ']" ';
            if (isset($this->fieldInfo['class']))
                $result .= 'class="' . $this->fieldInfo['class'] . '" ';
            $result .= '>';

            if ($val)
                $result .= htmlspecialchars(str_replace('<br />', '', $val));

            $result .= '</textarea>';

            return $result;
        }
    }
