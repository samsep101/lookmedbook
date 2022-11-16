<?php

    class Styled_checkboxType extends Type
    {

        var $nId;
        var $szDataName;

        public function getFormValue($val = '')
        {
            $value = $val ? 1 : 0;
            $label = (isset($this->fieldInfo['label'])) ? $this->fieldInfo['label'] : '';

			$class = ($value == 1) ? 'act' : '';
            $result = '<div class="chekBox '.$class.'">
                                <span></span>
                                '.$label.'
                                <input type="hidden" name="'.$this->getHtmlElementName().'" value="'.$value.'" />
                            </div>';
            return $result;
        }

        public function getViewValue($value)
        {

            if (empty($value)) {
                $result = 'Нет';
            } else {
                $result = 'Да';
            }
            return $result;
        }
    }