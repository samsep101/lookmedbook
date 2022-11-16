<?php

class Phone_textType extends Type
{

    public function getFormValue($val = '')
    {
        $result = '<span id="' . $this->getFieldName() .'">'.str_replace('-', '', $val).'</span>';

        if (isset($this->fieldInfo['script']))
        {
            $result .= '<script>'.$this->fieldInfo['script'].'</script>';
        }

        return $result;
    }

}