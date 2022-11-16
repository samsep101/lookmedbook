<?php

    class SimpleTextCrossType extends Type
    {

        public function getFormValue($val = '')
        {
            $db = Register::get('db');
            $szNameField = $this->fieldInfo['cross_name'];
            $szIndex = $this->fieldInfo['cross_index'];
            $szTable = $this->fieldInfo['cross_table'];

            $aResult = array();
            $aData = $db->query('SELECT ' . '`' . $szNameField . '`,`' . $szIndex . '` FROM `' . $szTable . '` WHERE `' . $szIndex . '` = ' . $this->value . '');

            $val = $aData[0][$szNameField];

            $szResult = '<span id="' . $this->fieldName . '_text">';
            if (isset($this->value))
                $szResult .= '<strong>' . htmlspecialchars($val) . '</strong>';
            elseif (!empty($val[$this->fieldName]))
                $szResult .= '...';
            $szResult .= '</span> <input type="hidden" id="' . $this->fieldName . '" name = "form[' . $this->fieldName . ']" value="' . htmlspecialchars($this->getValue()) . '" />';
            return $szResult;
        }

        public function getViewValue()
        {
            $db = Register::get('db');
            $szNameField = $this->fieldInfo['cross_name'];
            $szIndex = $this->fieldInfo['cross_index'];
            $szTable = $this->fieldInfo['cross_table'];

            $aResult = array();
            $aData = $db->query('SELECT ' . '`' . $szNameField . '`,`' . $szIndex . '` FROM `' . $szTable . '` WHERE `' . $szIndex . '` = ' . $this->value . '');

            $val = $aData[0][$szNameField];

            if (isset($this->value))
                $szResult = '' . htmlspecialchars($val) . '';
            elseif (!empty($val[$this->fieldName]))
                $szResult .= '...';
            return $szResult;
        }

    }