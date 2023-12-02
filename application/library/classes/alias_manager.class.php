<?php

class AliasManager extends ModelManager
{
    protected $transliterated_field = 'name';

    protected function beforeSave(DynamicModel $model)
    {
        if ($this->transliterated_field && !$model->alias) {
            $alias = StringTransliterationHelper::formatAlias($model->{$this->transliterated_field});

            if ($alias) {
                $alias_model = $this->getOneByAlias($alias);

                $i = 0;
                $unique_alias = $alias;
                while ($alias_model && ($alias_model->getId() != $model->getId())) {
                    $unique_alias = $alias . $i;
                    $alias_model = $this->getOneByAlias($unique_alias);
                    $i++;
                }

                $model->alias = $unique_alias;
            }
        }
    }

    /**
   * @param string $alias
   *
   * @return DynamicModel
   */
  public function getOneByAlias($alias)
  {
    $data = $this->orm_model->select()->where('alias = ?', $alias)->fetchOne();
    return $this->initOne($data);
  }

  public function getFirstByAliases(array $aliases)
  {
      $aliasesQ = implode(',', array_map(function ($a) {
          return "'{$this->db->escape($a)}'";
      }, $aliases));
      return $this->initOne(current($this->db->query("
          select *
          from `{$this->table_name}`
          where `alias` in ({$aliasesQ})
          order by field(`alias`, {$aliasesQ})
          limit 1
      ")));
  }

  /**
   * @param string|int $alias
   * @return DynamicModel
   */
  public function getOneByIdOrAlias($alias)
  {
    if (is_numeric($alias)) {
      return $this->getOneById((int)$alias);
    } else {
      return $this->getOneByAlias($alias);
    }
  }

    /**
     * @param string $alias
     * @return DynamicModel
     */
    public function getOneByOldAlias($alias)
    {
        $data = $this->orm_model->select()->where('old_alias = ?', $alias)->fetchOne();
        return $this->initOne($data);
    }

  public function getOneByIdOrAliasAndIsActive($alias)
  {
    if (is_numeric($alias)) {
      $data = $this->orm_model->select()->where('id = ?', (int)$alias)->fetchOne();
      return $this->initOne($data);
    } else {
      return $this->getOneByAlias($alias);
    }
  }
}
