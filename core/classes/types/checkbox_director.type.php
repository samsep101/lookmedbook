<?php

    class CheckboxDirectorType extends Type
    {

        var $nId;
        var $szDataName;

        public function getFormValue($val = '')
        {

            /* valid */
            if (!empty($val[$this->fieldName]))
                $this->setValue($val[$this->fieldName]);

            $director = RoleManager::getDirector();

            $value = $this->getValue();
            $roleId = (int)$_GET['id'];
            if (count($director) && ($roleId != $director['id'])) {
                $result = '<span class="grey">Уже выбран.</span>';
            } else {
                if (empty($value)) {
                    $value = 0;
                    $checked = '';
                } else {
                    $checked = 'checked';
                }

                $result = <<<EOD
<input type="hidden" id="{$this->fieldName}" name="form[{$this->fieldName}]" value="{$value}">
<input type="checkbox" id="{$this->fieldName}_checkbox" onclick="document.getElementById('$this->fieldName').value=(this.checked?1:0);" {$checked}> 
&nbsp;<label for="{$this->fieldName}_checkbox"> {$this->fieldInfo['label']} </label>
EOD;

            }
            return $result;
        }

        public function getViewValue()
        {
            $value = $this->getValue();
            if (empty($value)) {
                $result = 'Нет';
                //$value = 0;
            } else {
                $result = 'Да';
            }
            //$result='<img src="/media/images/checkbox'.$value.'.gif" >';
            return $result;
        }
    }