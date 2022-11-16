<?php

    class SmartSelectType extends Type
    {

        public function getFormValue($val = '')
        {
            $db = Register::get('db');

            $table = $this->fieldInfo['cross_table'];
            $field = $this->fieldInfo['cross_name'];
            $key = $this->fieldInfo['cross_index'];

            if (!$this->fieldInfo['parent']) {
                $data = $db->query("SELECT " . $field . ", " . $key . " FROM " . $table . ";");

                $result = '<select name="form[' . $this->getFieldName() . ']" id="field_' . $this->getFieldName() . '" style="width:50%">';

                if ($this->fieldInfo['first']) {
                    $result .= "<option value='0'>" . $this->fieldInfo['first'][0] . "</option>";
                }

                foreach ($data as $row) {
                    $selected = ($row['id'] == $this->value) ? " selected='selected'" : "";
                    $result .= "<option value='" . $row['id'] . "'" . $selected . ">" . $row['name'] . "</option>";
                }

                $result .= '</select>';
            } else {
                if ($this->value) {
                    $data = $db->query("SELECT " . $this->fieldInfo['parent']['key'] . " FROM " . $table . " WHERE id = " . $this->value . ";");
                    $kv = $data[0][$this->fieldInfo['parent']['key']];

                    if ($this->fieldInfo['parent']['key'] == "fk_producer") {
                        $data = $db->query("SELECT " . $field . ", " . $key . " FROM " . $table . " WHERE " . $this->fieldInfo['parent']['key'] . " = " . $kv . " AND set_isset = 1;");
                    } else {
                        $data = $db->query("SELECT " . $field . ", " . $key . " FROM " . $table . " WHERE " . $this->fieldInfo['parent']['key'] . " = " . $kv . ";");
                    }
                    $result = '<select name="form[' . $this->getFieldName() . ']" id="field_' . $this->getFieldName() . '" style="width:50%">';

                    if ($this->fieldInfo['first']) {
                        $result .= "<option value='0'>" . $this->fieldInfo['first'][0] . "</option>";
                    }

                    foreach ($data as $row) {
                        $selected = ($row['id'] == $this->value) ? " selected='selected'" : "";
                        $result .= "<option value='" . $row['id'] . "'" . $selected . ">" . $row['name'] . "</option>";
                    }

                    $result .= '</select>';
                } else {
                    $result = '<select name="form[' . $this->getFieldName() . ']" id="field_' . $this->getFieldName() . '" style="width:50%">';

                    if ($this->fieldInfo['first']) {
                        $result .= "<option value='0'>" . $this->fieldInfo['first'][0] . "</option>";
                    }

                    $result .= '</select>';
                }
            }

            $result .= '<script type="text/javascript">$(function(){';

            if (!$this->value && isset($this->fieldInfo['parent']))
                $result .= '
            if($("#field_' . $this->fieldInfo['parent']['field'] . '").val() != 0)
                ajaxNew("/ajax/getNestedSelect", {' . $this->fieldInfo['parent']['field'] . ': $("#field_' . $this->fieldInfo['parent']['field'] . '").val()}, "field_' . $this->getFieldName() . '");';

            if ($this->fieldInfo['child']) {


                $result .= '$("#field_' . $this->getFieldName() . '").change(function(){
                                if($("#field_' . $this->getFieldName() . '").val() == 0)
                                {
                                    $("#field_' . $this->fieldInfo['child'] . '").children().each(function(i){
                                        if(i != 0)
                                        {
                                            $(this).remove();
                                        }
                                        
                                    });
                                    $("#field_' . $this->fieldInfo['child'] . '").change();
                                }           
                                else
                                {
                                    ajaxNew("/ajax/getNestedSelect", {' . $this->getFieldName() . ': $("#field_' . $this->getFieldName() . '").val()}, "field_' . $this->fieldInfo['child'] . '");
                                    $("#field_' . $this->fieldInfo['child'] . '").change();
                                }
                            });
            ';
            }

            $result .= '});</script>';

            return $result;
        }

        public function getViewValue()
        {
            $db = Register::get('db');

            $table = $this->fieldInfo['cross_table'];
            $field = $this->fieldInfo['cross_name'];
            $key = $this->fieldInfo['cross_index'];

            $data = $db->query("SELECT " . $field . " FROM " . $table . " WHERE " . $key . " = " . $this->value . ";");

            if ($data[0][$field])
                return $data[0][$field];
            else
                return $this->fieldInfo['first'][0];
        }

    }