<?php
    class Ajax_checkboxType extends CheckboxType
    {
        public function getViewValue($value, $model)
        {
            $checked = $value ? 'checked="checked"' : '';
            return '<input type="checkbox" class="ajax-checkbox" data-id="'.$model->getId().'" data-field_name="'.$this->fieldName.'" '.$checked.' data-model_name="'.$this->table.'" />';
        }
    }

