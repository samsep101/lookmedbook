<?php

class CmsGeneratorConfig
{

  public $fields;
  public static $VALIDATE_FIELD_TEXT = 'text';
  public static $VALIDATE_FIELD_EMAIL = 'email';
  public static $VALIDATE_FIELD_EXIST = 'exist';
  public static $VALIDATE_FIELD_FILE = 'file';
  public static $VALIDATE_FIELD_SUBMIT = 'submit';
  public static $VALIDATE_FIELD_NUM = 'num';

  //	private $tabs;
  private $config;
  private $modelName;
  public $userFilter;

  public function __construct($modelName)
  {
    $this->modelName = $modelName;

    $config = CmsGeneratorConfigRegister::get($modelName);

    if ($config) {
      $this->config = $config;
      $this->initFields();
    }
  }

  public function getModelName()
  {
    return $this->modelName;
  }

  private function initFields()
  {

    $this->fields = array();

    foreach ($this->config['fields'] as $name => $field) {


      if (!is_array($field))
        $field = array('type' => $field);
      $fieldType = $field['type'];
      $fieldClass = ucfirst($fieldType) . 'Type';
      $this->fields[$name] = new $fieldClass($name, $field, NULL);
      $this->fields[$name]->table = $this->config['table'];
    }
  }

  public function prepereForPrint($data)
  {
    $titleData = array();
    foreach ($this->config['generator']['fields'] as $name => $field) {

      if (!($this->checkUserFilter($name) && Acl::userGrant($this->getModelName() . '_list_my'))) {
        $titleData[] = str_replace('&nbsp;', ' ', $field);
      }
    }

    $dataArr = array();
    $dataArr[] = $titleData;
    $i = 1;
    foreach ($data as $row) {
      $this->setValues($row);
      foreach ($this->getAllFields() as $field) {
        $dataArr[$i][] = $field->getViewValue();
      }
      $i++;
    }
    return $dataArr;
  }

  public function getFilters()
  {
    $filterArr = array();
    foreach ($this->config['fields'] as $name => $field) {
      if (is_array($field)) {
        foreach ($field as $name2 => $field2) {
          if ($name2 == 'filter' && $field2 == 'true') {
            if (!($this->checkUserFilter($name) && Acl::userGrant($this->getModelName() . '_list_my'))) {
              $filterArr[$name] = $this->getFieldLabel($name);
            }
          }
        }
      }
    }
    return $filterArr;
  }


  public function setValues($values)
  {
    $indexValue = NULL;
    foreach ($values as $fieldName => $fieldValue) {
      if (!empty($this->fields[$fieldName])) {
        $this->fields[$fieldName]->setValue($fieldValue);
        if ($this->fields[$fieldName]->fieldInfo['type'] == 'index')
          $indexValue = $fieldValue;
      }
    }

    foreach ($this->fields as $fieldName => $fieldValue) {
      if (!empty($this->fields[$fieldName]))
        $this->fields[$fieldName]->indexValue = $indexValue;
    }
  }

  public function checkUserFilter($fieldName)
  {
    if (isset($this->config['fields'][$fieldName]['userFilter']) && ($this->config['fields'][$fieldName]['userFilter'] == 'true'))
      return TRUE;
    return FALSE;
  }

  public function getFieldLabel($fieldName)
  {
    if (!empty($this->config['generator']['fields'][$fieldName]))
      return $this->config['generator']['fields'][$fieldName];

    $label = str_replace('_', ' ', $fieldName);
    $label = ucfirst($label);
    return $label;
  }

  public function getEditTabs()
  {
    if (empty($this->config['generator']['edit']['fields']))
      return NULL;
    $aTabs = array();
    foreach ($this->config['generator']['edit']['fields'] as $name => $fields)
      $aTabs[] = $name;
    return $aTabs;
  }

  public function getEditTabFields($tabName)
  {
    if (empty($this->config['generator']['edit']['fields'][$tabName]))
      return NULL;
    $aFields = array();

    foreach ($this->config['generator']['edit']['fields'][$tabName] as $fieldName)
      if (!empty($this->fields[$fieldName]))
        $aFields[$fieldName] = $this->fields[$fieldName];
    return $aFields;
  }

  public function getRequiredFields()
  {
    if (empty($this->config['generator']['required']))
      return NULL;

    $aFields = array();
    foreach ($this->config['generator']['required'] as $fieldType => $fieldCode) {
      $aFields[] = array(
        'label' => $this->config['generator']['fields'][$fieldType],
        'name' => $fieldType,
        'type' => $fieldCode
      );

    }
    return $aFields;
  }


  public function getAddTabs()
  {
    if (empty($this->config['generator']['add']['fields']))
      return NULL;
    $aTabs = array();
    foreach ($this->config['generator']['add']['fields'] as $name => $fields)
      $aTabs[] = $name;
    return $aTabs;
  }

  public function getAddTabFields($tabName)
  {
    if (empty($this->config['generator']['add']['fields'][$tabName]))
      return NULL;
    $aFields = array();
    foreach ($this->config['generator']['add']['fields'][$tabName] as $fieldName)
      if (!empty($this->fields[$fieldName]))
        $aFields[$fieldName] = $this->fields[$fieldName];
    return $aFields;
  }

  public function getListFields()
  {
    if (empty($this->config['generator']['list']['fields']))
      return NULL;
    $aFields = array();
    $this->userFilter = '';
    foreach ($this->config['generator']['list']['fields'] as $fieldName) {
      if (!empty($this->fields[$fieldName])) {
        //Проверка на userFilter
        if ($this->checkUserFilter($fieldName) && Acl::userGrant($this->getModelName() . '_list_my')) {
          if ($this->userFilter) {
            $this->userFilter .= ' and ';
          } else {
            $this->userFilter .= ' ' . $fieldName . '=' . Acl::userId();
          }
        }

        if (!($this->checkUserFilter($fieldName) && Acl::userGrant($this->getModelName() . '_list_my'))) {
          $aFields[$fieldName] = $this->fields[$fieldName];
        }
      }
    }
    return $aFields;
  }

  public function getAllFields()
  {
    if (empty($this->config['generator']['fields']))
      return NULL;
    $aFields = array();
    $this->userFilter = '';
    foreach ($this->config['generator']['fields'] as $fieldName => $name) {
      if (!empty($this->fields[$fieldName])) {
        if (!($this->checkUserFilter($fieldName) && Acl::userGrant($this->getModelName() . '_list_my'))) {
          $aFields[$fieldName] = $this->fields[$fieldName];
        }
      }
    }
    return $aFields;
  }


  public function getListTitle()
  {
    if (empty($this->config['generator']['list']['title']))
      return $this->modelName;
    return $this->config['generator']['list']['title'];
  }

  public function getListJoins()
  {
    if (isset($this->config['generator']['list']['join']))
      return $this->config['generator']['list']['join'];

    return array();
  }

  public function getListTotalCount()
  {
    if (isset($this->config['generator']['list']['total_count']))
      return $this->config['generator']['list']['total_count'];

    return array();
  }

  public function getAdditionalHTML()
  {
    if (isset($this->config['generator']['list']['additionalHTML']))
      return $this->config['generator']['list']['additionalHTML'];

    return '';
  }

  public function getListFilters()
  {
    if (isset($this->config['generator']['list']['filters']))
      return $this->config['generator']['list']['filters'];

    return array();
  }

  public function getListOrder()
  {
    if (empty($this->config['generator']['list']['order']))
      return '';
    return $this->config['generator']['list']['order'];
  }

  public function getListRules()
  {
    if (Acl::userGrant($this->modelName . '_list'))
      return TRUE;
    return FALSE;
  }

  public function getListInformationBlocks()
  {
    if (!isset($this->config['generator']['list']['information_blocks']))
      return array();
    return $this->config['generator']['list']['information_blocks'];
  }

  public function getListButtons()
  {
    if (!isset($this->config['generator']['list']['buttons']))
      return array();
    return $this->config['generator']['list']['buttons'];
  }

    public function getListLinks()
    {
        if (!isset($this->config['generator']['list']['links']))
            return array();
        return $this->config['generator']['list']['links'];
    }

    /**
     * @return callable|bool
     */
    public function getListLinkGenerator()
    {
        if (!isset($this->config['generator']['list']['link_generator']))
            return false;
        return $this->config['generator']['list']['link_generator'];
    }

  public function getAddTitle()
  {
    if (empty($this->config['generator']['add']['title']))
      return $this->modelName;
    return $this->config['generator']['add']['title'];
  }

  public function getAddTooltip()
  {
    if (empty($this->config['generator']['add']['tooltip']))
      return '';
    return $this->config['generator']['add']['tooltip'];
  }

  public function getEditTooltip()
  {
    if (empty($this->config['generator']['edit']['tooltip']))
      return '';
    return $this->config['generator']['edit']['tooltip'];
  }

  public function getEditTitle()
  {
    if (empty($this->config['generator']['edit']['title']))
      return $this->modelName;
    return $this->config['generator']['edit']['title'];
  }

  public function getTable()
  {
    if (empty($this->config['table']))
      return DB_PREFIX . $this->modelName;
    return $this->config['table'];
  }

  public function getTitle()
  {
    if (empty($this->config['title']))
      return $this->modelName;
    return $this->config['title'];
  }

  public function getParentTitle()
  {
    if (empty($this->config['parent']['title']))
      return NULL;
    return $this->config['parent']['title'];
  }

  public function getParentUrl()
  {
    if (empty($this->config['parent']['url']))
      return NULL;
    return $this->config['parent']['url'];
  }

  public function getIndexField()
  {
    foreach ($this->config['fields'] as $name => $field) {
      $type = (!is_array($field)) ? $field : $field['type'];
      if ($type == 'index')
        return $name;
    }
    return NULL;
  }

  public function getSortBy()
  {
    return isset($this->config['generator']['list']['sort_by']) ? $this->config['generator']['list']['sort_by'] : array();
  }

  public function isSortableList()
  {
    return isset($this->config['generator']['list']['sortable']) ? $this->config['generator']['list']['sortable'] : false;
  }

  public function getSaveData($data)
  {
    $formData = array();
    $indexValue = NULL;
    foreach ($data as $key => $value) {
      if (!empty($this->fields[$key])) {
        if ($this->fields[$key]->fieldInfo['type'] == 'index')
          $indexValue = $this->fields[$key]->getSaveValue($value);
      }
    }
    foreach ($data as $key => $value) {
      if (!empty($this->fields[$key])) {
        $this->fields[$key]->indexValue = $indexValue;
        $fieldSaveValue = $this->fields[$key]->getSaveValue($value);
        if ($fieldSaveValue != Type::NOT_SET)
          $formData[$key] = $fieldSaveValue;
      }
    }
    return $formData;
  }

  public function getExtra()
  {
    return (isset($this->config['extra'])) ? $this->config['extra'] : array();
  }

  public function getWhere()
  {
    return (isset($this->config['generator']['list']['where'])) ? $this->config['generator']['list']['where'] : array();
  }

  public function getLegend()
  {
    return (isset($this->config['generator']['list']['legend'])) ? $this->config['generator']['list']['legend'] : array();
  }

  public function getListTemplate()
  {

  }

  public function getFormTemplate()
  {

  }

    public function displayButtons() {

        if(!empty($this->config['buttons'])){

            $html = [];
            foreach($this->config['buttons'] as $title => $trigger){
                
                $html[] = sprintf('<input type="button" data-trigger="%s" value="%s">', $trigger, $title);
            }

            return implode(PHP_EOL, $html);
        }

        return '';
    }
}