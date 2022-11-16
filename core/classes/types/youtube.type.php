<?php

    class YoutubeType extends Type
    {

        public function getViewValue()
        {
            //return '<textarea name="form[]'
            //return mb_substr(htmlspecialchars($this->value),0,100,'UTF-8')."...";
            //return '<i>...текст... (для просмотра нажмите "Редактировать")</i>';
        }

        public function getFormValue($val = '')
        {

            $result = '<textarea ';
            if (isset($this->fieldInfo['style'])) {
                $result .= ' style="' . $this->fieldInfo['style'] . '" ';
            } else {
                $result .= ' style="width:99%" rows=12 ';
            }
            $result .= 'name="form[' . $this->fieldName . ']" ';
            if (isset($this->fieldInfo['class']))
                $result .= 'class="' . $this->fieldInfo['class'] . '" ';
            $result .= '>';
            if (isset($this->value))
                $result .= htmlspecialchars(str_replace('<br />', '', $this->value));
            elseif (!empty($val[$this->fieldName]))
                $result .= htmlspecialchars($val[$this->fieldName]);
            $result .= '</textarea>';

            return $result;
        }

    }