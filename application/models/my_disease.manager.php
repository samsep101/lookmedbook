<?php

class MyDiseaseManager extends ModelWAccountidManager
{
  //todo: tests

  protected $table_name = 'my_disease';
  protected $model_name = 'MyDiseaseModel';

  public function checkExistsByDiseaseIdAndAccountId($disease_id, $account_id)
  {
    $sql = 'SELECT COUNT(*) as `result`
                    FROM ' . $this->table_name . '
                    WHERE `disease_id` = ' . (int)$disease_id . '
                    AND `account_id` = ' . (int)$account_id;

    $data = $this->db->query($sql);

    return (bool)$data[0]['result'];
  }

  /**
   * @return MyDiseaseModel
   */
  public function getOneByDiseaseIdAndAccountId($disease_id, $account_id)
  {
    $sql = 'SELECT ' . $this->selected_fields . '
                    FROM `' . $this->table_name . '`
                    WHERE `disease_id` = ' . (int)$disease_id . '
                    AND `account_id` = ' . (int)$account_id;

    $data = $this->db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  /**
   * @return MyDiseaseModel[]
   */
  public function getListByAccountId($account_id)
  {
    $data = $this->orm_model->select()->where('account_id = ? AND is_archive != 1', (int)$account_id)->fetchAll();
    return (isset($data)) ? $this->initList($data) : array();
  }

  /**
   * @return MyDiseaseModel[]
   */
  /**
   * @return MyDiseaseModel[]
   */
  public function getListByAccountIdAndIsArchive($account_id)
  {
    $data = $this->orm_model->select()->where('account_id = ? AND is_archive = 1', (int)$account_id)->fetchAll();
    return (isset($data)) ? $this->initList($data) : array();
  }


  /**
   * @return MyDiseaseModel[]
   */
  public function getListByAccountIdWithPagging($account_id, $page, $per_page)
  {
    $page = ($page - 1) * $per_page;
    $data = $this->orm_model->select()->where('account_id = ? AND is_archive != 1', (int)$account_id)->limit($page, $per_page)->order('dt DESC')->fetchAll();
    return (isset($data)) ? $this->initList($data) : array();
  }


  /**
   * @return MyDiseaseModel[]
   */
  public function getListByAccountIdAndIsArchiveWithPaging($account_id, $page, $per_page)
  {
    $page = ($page - 1) * $per_page;
    $data = $this->orm_model->select()->where('account_id = ? AND is_archive = 1', (int)$account_id)->limit($page, $per_page)->order('dt DESC')->fetchAll();
    return (isset($data)) ? $this->initList($data) : array();
  }

  // todo: имя не совпадает
  public function getDiseaseNamesByFirstLetterWithPadding($account_id, $letter, $page, $per_page)
  {

    $page = ($page - 1) * $per_page;
    $sql = 'SELECT md.*
                    FROM my_disease md
                    INNER JOIN disease d ON md.disease_id = d.id
                    WHERE md.account_id = ' . (int)$account_id . '
                        AND d.title LIKE "' . $this->db->escape($letter) . '%"
                        AND md.is_archive != 1
                    ORDER BY d.title
                    LIMIT ' . $page . ',' . $per_page . ';';
    $data = $this->db->query($sql);

    return $this->initList($data);
  }


  public function getDiseaseNamesByFirstLetterAndIsArchiveWithPadding($account_id, $letter, $page, $per_page)
  {
    $page = ($page - 1) * $per_page;
    $sql = 'SELECT md.*
                    FROM my_disease md
                    INNER JOIN disease d ON md.disease_id = d.id
                    WHERE md.account_id = ' . (int)$account_id . '
                    AND md.is_archive = 1
                    AND d.title LIKE "' . $this->db->escape($letter) . '%"
                    ORDER BY d.title
                    LIMIT ' . $page . ',' . $per_page . ';';
    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function getDiseaseNamesByFirstLetter($account_id, $letter)
  {
    $sql = 'SELECT md.*
                    FROM my_disease md
                    INNER JOIN disease d ON md.disease_id = d.id
                    WHERE md.account_id = ' . (int)$account_id . '
                    AND d.title LIKE "' . $this->db->escape($letter) . '%"
                    ORDER BY d.title;';
    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function getDiseaseNamesByFirstLetterAndIsArchive($account_id, $letter)
  {
    $sql = 'SELECT md.*
                    FROM my_disease md
                    INNER JOIN disease d ON md.disease_id = d.id
                    WHERE md.account_id = ' . (int)$account_id . '
                    AND md.is_archive = 1
                    AND d.title LIKE "' . $this->db->escape($letter) . '%"
                    ORDER BY d.title;';
    $data = $this->db->query($sql);

    return $this->initList($data);
  }

}
