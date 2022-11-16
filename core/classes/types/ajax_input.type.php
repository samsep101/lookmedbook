<?php

class Ajax_inputType extends Type
{

    public function getFormValue($val = '')
    {
        $doctor = ModelManagerFactory::getByName('doctor')->getOneById($val);
        $doctor_name = $doctor ? $doctor->full_name : '';
        $result = '<div class="quick-search" id="doctor-pick-quick-search">
                       <input type="text" class="doctor-pick-input" autocomplete="off" value="'.$doctor_name.'"/>
                       <input type="hidden" name="form['.$this->fieldName.']" value="'.$val.'"/>
                       <ul class="drop-menu" style="display: none;">
                       </ul>
                   </div>';
        return $result;
    }

    public function getViewValue($value)
    {
        $model = ModelManagerFactory::getByName($this->fieldInfo['cross_table'])->getOneById($value);
        if (!$model) {
            if (isset($this->fieldInfo['if_null']))
                return $this->fieldInfo['if_null'];
            else
                return NULL;
        }

        $field = $this->fieldInfo['cross_name'];
        return '<a href="' . ADMIN_FOLDER . '/' . $this->fieldInfo['cross_table'] . '/edit?id=' . $model->getId() . '" target="_blank">' . $model->$field . '</a>';
    }

}