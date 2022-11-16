<?php

    class CategoryUserType extends Type
    {

        public function getFormValue($val = '')
        {
            $db = Register::get('db');

            $valid = @$val[$this->fieldName];

            $szNameField = $this->fieldInfo['cross_name'];
            $szIndex = $this->fieldInfo['cross_index'];
            $szTable = $this->fieldInfo['cross_table'];
            //$szOrder = $this->fieldInfo['ordered'];

            if (!empty($this->fieldInfo['cross_cond'])) {
                $szCond = ' WHERE ' . $this->fieldInfo['cross_cond'];
            } elseif (!empty($this->fieldInfo['cross_group'])) {
                $szCond = ' WHERE `' . $this->fieldInfo['cross_parent'] . '`="' . $data->nID . '"';
            } else $szCond = '';

            if (!empty($szOrder)) {
                $szCond .= ' ORDER BY `' . $szOrder . '`';
            } else {
                $szCond .= ' ORDER BY `' . $szIndex . '`';
            }

            $aResult = array();
            $aData = $db->query('SELECT concat(last_name," ",first_name," ",second_name," ") as ' . '`' . $szNameField . '`,`' . $szIndex . '` FROM `' . $szTable . '`' . $szCond);

            $aParse = array();
            $result = '<select name="form[' . $this->getFieldName() . ']" style="width:50%">';

            if (!empty($this->fieldInfo['first'])) {
                foreach ($this->fieldInfo['first'] as $key=> $value) {
                    if ($value == $this->value)
                        $selected = 'selected';
                    elseif ($value == $valid)
                        $selected = 'selected'; else
                        $selected = '';
                    $result .= '<option value="' . $key . '" ' . $selected . '>' . htmlspecialchars($value) . '</option>';
                }
            }
            foreach ($aData as $key=> $value) {

                if (isset($_REQUEST['fk']) && $_REQUEST['fk'] == $value[$szIndex])
                    $selected = 'selected';
                elseif ($value[$szIndex] == $this->value)
                    $selected = 'selected'; elseif ($value[$szIndex] == $valid)
                    $selected = 'selected'; else
                    $selected = '';

                $result .= '<option value="' . $value[$szIndex] . '" ' . $selected . '>' . htmlspecialchars($value[$szNameField]) . '</option>';
            }
            $result .= "</select>";

            return $result;
        }

        public function getViewValue()
        {
            $current = $this->getValue();
            if (empty($current))
                return NULL;
            $db = Register::get('db');
            $szTable = $this->fieldInfo['cross_table'];
            $szIndex = $this->fieldInfo['cross_index'];
            $szNameField = $this->fieldInfo['cross_name'];

            $aResult = $db->query('SELECT tt.*, concat(last_name," ",name," ",middle_name," (", (select name from ' . DB_PREFIX . 'role where id = role_id),")") as ' . '`' . $szNameField . '`  FROM `' . $szTable . '` tt WHERE `' . $szIndex . '`=' . $this->value . ' LIMIT 0,1');
            $szDefault = isset($this->fieldInfo['default']) ? $this->fieldInfo['default'] : '&nbsp;';
            $szResult = (!empty($aResult[0][$szNameField]) ? $aResult[0][$szNameField] : $szDefault);
            return $szResult;
        }

    }