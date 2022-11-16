<?php

    class ReferenceType extends Type
    {
        public function getFormValue($val = '', $model = null)
        {
            if ($val) {
                $result = '<a href="' . SITE_URL . '/admin/' . $this->fieldInfo['table'] . '/edit/?id=' . $val->id . '&destination=">' . $val->{$this->fieldInfo['field']} . '</a>';
                return $result;
            } else {
                return '';
            }


        }
    }