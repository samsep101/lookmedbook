<?php

    class HiddenType extends Type
    {

        public $hasLayout = FALSE;

        public function getFormValue($val = '')
        {

            //var_dump($val[$this->fieldName]);

            if (isset($this->value))
                return '<input name="form[' . $this->fieldName . ']" id="form[' . $this->fieldName . ']" type="hidden" value="' . $this->value . '">';
            elseif (!empty($val[$this->fieldName]))
                return '<input name="form[' . $this->fieldName . ']" id="form[' . $this->fieldName . ']" type="hidden" value="' . htmlspecialchars($val[$this->fieldName]) . '">';
        }

    }