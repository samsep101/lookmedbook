<?php

    class Parent_record_one_tableType extends Type
    {
        public function getFormValue($parent_id = '')
        {
            $result   = '<select name="form[' . $this->getFieldName() . ']" style="' . $this->fieldInfo['style'] . '">';
            $selected = 'selected="selected"';


            if(!empty($this->fieldInfo['first']))
            {
                    $result .= '<option value="0" ' . $selected . '>' . $this->fieldInfo['first'] . '</option>';
            }

            $list = ModelManagerFactory::getByName($this->fieldInfo['manager'])->getList();

            foreach($list as $value)
            {
                $result .= '<option value="' . $value->getId() . '"';

                if($value->getId() == $parent_id)
                {
                    $result .= $selected;
                }

                $result .= ' >' . htmlspecialchars(str_replace('<br />', '', $value->{$this->fieldInfo['name']})) . '</option>';
            }

            $result .= "</select>";


            return $result;
        }

        public function getViewValue($value)
        {
            $model = ModelManagerFactory::getByName($this->fieldInfo['table_name'])->getOneById($value);
            if(!$model)
            {
                if(isset($this->fieldInfo['if_null']))
                    return $this->fieldInfo['if_null'];
                else
                    return NULL;
            }

            $field = $this->fieldInfo['name'];

            return '<a href="' . ADMIN_FOLDER . '/' . $this->fieldInfo['manager'] . '/edit?id=' . $model->getId() . '" target="_blank">' . $model->$field . '</a>';
        }
    }