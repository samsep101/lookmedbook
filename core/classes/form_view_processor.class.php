<?php

class FormViewProcessor
{
  private $model;
  private $field_types = array();
  private $model_name = NULL;

  private $is_list_element = FALSE;

  public function __construct($config_name, DynamicModel $model = NULL)
  {
    $this->model = $model;
    $config = CmsGeneratorConfigRegister::get($config_name);

    $this->field_types = $config['fields'];
    $this->model_name = $config['title'];
  }

  public function setIsListElement($flag)
  {
    $this->is_list_element = $flag;
  }

  public function getFieldType($field_name)
  {
    return empty($this->field_types[$field_name])?'':$this->field_types[$field_name];
  }

  public function getView($field_name, $field_data = null)
  {
    $type_view = TypeViewFactory::getTypeViewByTypeName($this->field_types[$field_name]);
    $type_view->setFieldName($field_name);

    if ($this->is_list_element) {
      $type_view->setNameStyle('simple');
    }
    if ($type_view instanceof List_checkboxType) {
      return $type_view->getFormValue($field_data);
    } else {
      return $type_view->getFormValue($this->model->{$field_name});
    }

  }
}