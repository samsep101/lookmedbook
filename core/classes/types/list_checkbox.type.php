<?php

class List_checkboxType extends Type
{
    private function getListTypesForEntity($entity_id, $entity_type_manager)
    {
        $types = array();

        if ($entity_id > 0)
        {
            $db = Register::get('db');

            $sql = 'SELECT *
                    FROM `' . $this->fieldInfo['cross_table_name'] . '`
                    WHERE `' . $this->fieldInfo['result_field_name'] . '` = ' . $entity_id;

            $data = $db->query($sql);

            if (count($data) > 0)
            {
                foreach ($data AS $dKey => $dValue)
                {
                    $types[] = $entity_type_manager->getOneById($dValue[$this->fieldInfo['cross_field_name']]);
                }
            }
        }

        return count($types) > 0 ? $types : array();
    }

    private function getTypesToIdentifyTypesTheCurrentEntity($entity_id = 0)
    {
        $entity_type_manager = ModelManagerFactory::getByName($this->fieldInfo['result_table_name']);
        $total_types         = $entity_type_manager->getList();
        $types_for_entity    = $this->getListTypesForEntity($entity_id, $entity_type_manager);

        if (count($types_for_entity) > 0)
        {
            $types_for_entity_ids = array();
            foreach ($types_for_entity AS $tfcValue) $types_for_entity_ids[] = $tfcValue->id;

            foreach ($total_types AS &$ttValue)
            {
                if (in_array($ttValue->id, $types_for_entity_ids))
                {
                    $ttValue->selected_for_clinic = 1;
                }
            }
        }

        return $total_types;
    }

    public function getFormValue($model = FALSE)
    {
        $aData = $this->getTypesToIdentifyTypesTheCurrentEntity(intval($model->id));

        $result = '<div class="list_checkbox">';

        $counter = 1;
        $totalCounts = count($aData);
        foreach ($aData as $value)
        {
            $class = '';
            $fieldValue = 1;

            if (!empty($value->selected_for_clinic))
                $checked = 'checked';
            else
                $checked = '';

            $label = $value->name;

            if ($checked)
            {
                $class = ' act';
            } else {
                $fieldValue = 0;
            }

            if (!empty($this->fieldInfo['style_class'])) $class .= ' ' . $this->fieldInfo['style_class'];
            $result .= '<div class="chekBox' . $class . '">
                                <span></span>
                                <span class="list_checkbox_label">' . $label . '</span>
                                <input type="hidden" name="lists[' . $this->getFieldName() . '][' . $value->id . ']" value="' . $fieldValue . '" />
                            </div>';
        }
        $result .= '</div>';

        return $result;
    }

}