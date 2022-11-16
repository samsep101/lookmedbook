<?php

    class RadioType extends Type
    {

        var $nId;
        var $szDataName;

        public function getFormValue($val = '')
        {
            $result = '';
            foreach ($this->fieldInfo['values'] as $value => $label)
            {
                $result .= '<input type="radio" name="form['.$this->fieldName.']" value="'.$value.'"><span name="'.$this->fieldName.'_'.$value.'">'.$label.'</span><br>';
            }

            if ($val){
                $result .='
                <script>
                    $("input:radio").each(function(){
                        if ($(this).val() == '.$val.')
                            $(this).attr("checked", "checked");
                    });
                </script>';
            };

            if (isset($this->fieldInfo['text']))
            {
                $result .= $this->fieldInfo['text'];
            }

            return $result;
        }

        public function getViewValue($value)
        {
            //Test::dump($this->fieldInfo['values'][$value]);
            if ($value)
                return $this->fieldInfo['values'][$value];
            else
                return '';
        }
    }