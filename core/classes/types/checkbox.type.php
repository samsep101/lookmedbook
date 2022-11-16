<?php

    class CheckboxType extends Type
    {

        var $nId;
        var $szDataName;

        public function getFormValue($val = '')
        {

            /* valid */
            if (!empty($val[$this->fieldName]))
                $this->setValue($val[$this->fieldName]);

            $value = $val;
            if (empty($value)) {
                $value = 0;
                $checked = '';
            } else {
                $checked = 'checked';
            }
            $label = (isset($this->fieldInfo['label'])) ? $this->fieldInfo['label'] : '';
            $result = <<<EOD
<input type="hidden" id="{$this->fieldName}" name="form[{$this->fieldName}]" value="{$value}">
<input type="checkbox" id="{$this->fieldName}_checkbox" onclick="document.getElementById('$this->fieldName').value=(this.checked?1:0);" {$checked}>
&nbsp;
<label class="label-for-checkbox" for="{$this->fieldName}_checkbox">{$label}</label>
EOD;
            return $result;
        }

        public function getViewValue($value)
        {

            if (empty($value)) {
                $result = 'Нет';
                //$value = 0;
            } else {
                $result = 'Да';
            }
            //$result='<img src="/media/images/checkbox'.$value.'.gif" >';
            return $result;
        }
    }