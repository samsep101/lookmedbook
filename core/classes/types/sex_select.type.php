<?php

    class Sex_selectType extends Type
    {

        var $nId;
        var $szDataName;

        public function getFormValue($val = '')
        {
            $result = '<div class="gender flo gender-select">
                             <span class="man"></span>
                             <span class="woman"></span>
                             <input type="hidden" value="'.$val.'" name="form['.$this->fieldName.']">
                        </div>';

            return $result;
        }
    }