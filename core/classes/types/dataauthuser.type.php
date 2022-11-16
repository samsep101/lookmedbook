<?php

    class dataAuthUserType extends Type
    {

        public function getFormValue($val = '')
        {
            $actualVal = $val ? $val : ($this->value ? $this->value : '');
            $szResult = '<span id="' . $this->fieldName . '_text">';

            if(!empty($actualVal)) {
                $db = Register::get('db');
                $szNameField = $this->fieldInfo['cross_name'];
                $szIndex = $this->fieldInfo['cross_index'];
                $szTable = $this->fieldInfo['cross_table'];

                $aData = $db->query('SELECT ' . '`' . $szNameField . '`,`' . $szIndex . '` FROM `' . $szTable . '` WHERE `' . $szIndex . '` = ' . $actualVal . '');

                $val = $aData[0][$szNameField];

                if (!is_array($val)) {
                    $szResult .= '<strong>' . htmlspecialchars($val) . '</strong>';
                }
            } else {
                $szResult .= 'Не обработана';
            }

            $szResult .= '</span>';
//            $szResult .= '</span> <input type="hidden" id="' . $this->fieldName . '" name = "form[' . $this->fieldName . ']" value="' . Acl::userId() . '" />';

            return $szResult;
        }

        public function getViewValue($val = '')
        {
            $actualVal = $val ? $val : ($this->value ? $this->value : '');
            $szResult = 'Не обработана';

            if(!empty($actualVal)) {
                $db = Register::get('db');
                $szNameField = $this->fieldInfo['cross_name'];
                $szIndex = $this->fieldInfo['cross_index'];
                $szTable = $this->fieldInfo['cross_table'];


                $aResult = array();
                $aData = $db->query('SELECT ' . '`' . $szNameField . '`,`' . $szIndex . '` FROM `' . $szTable . '` WHERE `' . $szIndex . '` = ' . $actualVal . '');

                $val = $aData[0][$szNameField];

                if (!is_array($val)) {
                    $szResult = '' . htmlspecialchars($val) . '';
                }
            }

            return $szResult;
        }

    }