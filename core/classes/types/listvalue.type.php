<?php

    class ListvalueType extends Type
    {

        public function __getValue()
        {
            if (array_key_exists($this->value, $this->fieldInfo['values']))
                if (is_array($this->fieldInfo['values'][$this->value]))
                    if (!isset($this->fieldInfo['values'][$this->value]['value']))
                        postError('Clistvalue::__getValue проблема');
                    else
                        return $this->fieldInfo['values'][$this->value]['value'];
                else
                    return $this->fieldInfo['values'][$this->value];
            else {

                return '&nbsp;';
            }
        }

        public function getFormValue($val = '')
        {

            $valid = @$val[$this->fieldName];

            if (!empty($this->fieldInfo['size']))
                $szSize = $this->fieldInfo['size'];
            else
                $szSize = 1;
            if (!empty($this->fieldInfo['class']))
                $szClass = $this->fieldInfo['class'];
            else
                $szClass = '';
            $result = '<select size="' . $szSize . '" name="form[' . $this->fieldName . ']" class="' . $szClass . '">';
            $szName = '';
    
            foreach ($this->fieldInfo['values'] as $key=> $value) {
                $szSelected = '';
                if ($key == $val) {
                    $szSelected = 'selected';
                    $szName = $value;
                } else if (isset($this->fieldInfo['selected']) && $this->fieldInfo['selected'] == $key && !$val) {
                	$szSelected = 'selected';
            	} else if ($key == $valid) {
                    $selected = 'selected';
                    $szName = $value;
                }
                
                if (is_array($value)) {
                    if (!empty($value['class']))
                        $szClass = $value['class'];
                    else
                        $szClass = '';
                    $result .= '<option value="' . $key . '" class="' . $szClass . '" ' . $szSelected . '>' . $value['value'] . '</option>' . "\n";
                } else
                    $result .= '<option value="' . $key . '" ' . $szSelected . '>' . $value . '</option>' . "\n";
            }
            $result .= '</select>';
            return $result;
        }

        public function check($value, $fieldInfo)
        {
            foreach ($fieldInfo['values'] as $key=> $lvalue) {
                if ($value == $key) {
                    return TRUE;
                }
            }
            return FALSE;
        }

        public function getViewValue($val)
        {
            return (isset($this->fieldInfo['values'][$val])) ? $this->fieldInfo['values'][$val] : '';
        }
    }