<?php

class CategoryType extends Type
{

  public function getFormValue($val = '', $model = false)
  {
    $valid = $val;

    $sort_param = isset($this->fieldInfo['sort_by']) ? $this->fieldInfo['sort_by'] : '';
    $params = [];

    if (isset($this->fieldInfo['where'])) {
      $params[] = [$this->fieldInfo['where']['param'], $this->fieldInfo['where']['value']];
    }

    if (isset($this->fieldInfo['search_params']) && $model) {
      foreach ($this->fieldInfo['search_params'] as $param => $field_name) {
        if ($param == 'join') {
          $params[] = ['join', $field_name];
        } else {
          if ($field_name) {
            if (is_array($field_name)) {
              $params[] = [$field_name['field_name'], $field_name['value']];
            } else {
              $params[] = [$param, $model->{$field_name}];
            }
          } else {
            $params[] = [$param, $model->{$field_name}];
          }
        }
      }
    }

    $style = isset($this->fieldInfo['style']) ? $this->fieldInfo['style'] : 'width: 50%';

    $tag_class = 'form__' . $this->getFieldName();

    $result = '<div class="cross_name ' . $tag_class . '" style="display: none;">' . json_encode($this->fieldInfo['cross_name']) . '</div>';
    $result .= '<div class="cross_table ' . $tag_class . '" style="display: none;">' . json_encode($this->fieldInfo['cross_table']) . '</div>';
    $result .= '<div class="sort_param ' . $tag_class . '" style="display: none;">' . json_encode($sort_param) . '</div>';
    $result .= '<div class="search_param ' . $tag_class . '" style="display: none;">' . json_encode($params) . '</div>';
    $result .= '<div class="value ' . $tag_class . '" style="display: none;">' . str_replace('<', '%$%', $val) . '</div>';


    if (isset($this->fieldInfo['short'])) {
      $result .= '<select class="filling_select ' . $tag_class . '" name="form[' . $this->getFieldName() . ']" style="' . $style . '" >';

      if (!empty($this->fieldInfo['first'])) {
        foreach ($this->fieldInfo['first'] as $key => $value) {
          if ($value == $this->value){
            $selected = 'selected';
          }elseif ($value == $valid) {
              $selected = 'selected';
          }else{
              $selected = '';
          }
          $result .= '<option value="' . $key . '" ' . $selected . ' class="first">' . htmlspecialchars($value) . '</option>';
        }
      }
      $result .= "</select>";
    } else {
      $result .= '<input class="filling_input ' . $tag_class . '" style="' . $style . '" />';
      $result .= '<div class="filling_select ' . $tag_class . '" style="' . $style . '; display: none;" ></div>';
      $result .= '<input class="filling_value ' . $tag_class . '" name="form[' . $this->getFieldName() . ']" style="display: none;" value="' . htmlspecialchars($val) . '" />';
    }

    if (isset($this->fieldInfo['script'])) {
      $result .= '<script>' . $this->fieldInfo['script'] . '</script>';
    }
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