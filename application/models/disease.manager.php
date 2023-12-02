<?php

class DiseaseManager extends AliasManager
{
  protected $table_name = 'disease';
  protected $model_name = 'DiseaseModel';

  protected $transliterated_field = 'title';

  public function beforeSave(DynamicModel $disease)
  {
    $word_decline = WordDeclination::getInstance();

    if (!$disease->genitive_name) {
      $disease->genitive_name = $word_decline->toGenitive($disease->title);
    }
    if (!$disease->prepositional_name) {
      $disease->prepositional_name = $word_decline->toPrepositional($disease->title);
    }

    if (!$disease->description)
      $disease->description = DiseaseDescriptionGenerator::generate($disease);

    parent::beforeSave($disease);
  }

  protected function afterSave(DynamicModel $model)
  {
      parent::afterSave($model);

      ElasticaTask::indexDisease($model);
  }

    public function getActiveListByTitleOrAltName($query, $by_page, $page, $get_extra_entry = 0)
  {
    $criteria = new DiseaseSearchCriteria();
    $criteria->page = $page;
    $criteria->by_page = $by_page;
    $criteria->name = $query;
    $criteria->get_extra_item = $get_extra_entry;

    return $this->getListByModelSearchCriteria($criteria);
  }

  /**
   * @return DiseaseModel[]
   */
  public function getListByDiseaseTagId($disease_tags, $by_page, $page, $get_extra_entry = 0)
  {
    $offset = ($page - 1) * $by_page;
    if ($get_extra_entry) {
      $by_page++;
    }

    $db = Register::get('db');

    $tags_string = '';
    foreach ($disease_tags as $disease_tag) {
      if ($tags_string != '') {
        $tags_string .= ',';
      }
      $tags_string .= $disease_tag->id;
    }

    $sql = 'SELECT *
        FROM disease
        WHERE (
          SELECT COUNT(*)
          FROM disease_to_disease_tag
          WHERE disease_id = disease.id
          AND disease_tag_id IN (' . $tags_string . ')
        )>0
        AND is_active = 1
        GROUP BY disease.id
        LIMIT ' . $offset . ',' . $by_page;

    $data = $db->query($sql);

    return (count($data)) ? $this->initList($data) : array();
  }

  public function getSelectedListByAccountId($account_id)
  {
    $sql = 'SELECT d.*
          FROM disease d
          INNER JOIN my_disease m ON m.disease_id = d.id
          WHERE m.account_id = ' . (int)$account_id . '
          ORDER BY m.dt DESC';
    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  /**
   * @return DiseaseModel[]
   */
  public function getActiveList()
  {
    $data = $this->orm_model->select()->where('is_active = 1')->order('title ASC')->fetchAll();
    return $this->initList($data);
  }

  public function getActiveListByPage($page, $by_page)
  {
    if ($page && $by_page) {
      $data = $this->orm_model->select()->where('is_active = 1')->order('title ASC')->limit($page, $by_page)->fetchAll();
    } else {
      $data = $this->orm_model->select()->where('is_active = 1')->order('title ASC')->fetchAll();
    }

    return $this->initList($data);
  }

  public function getIdByTitle($title)
  {
    $sql = 'SELECT id
          FROM ' . $this->table_name . '
          WHERE title = "' . $this->db->escape($title) . '"';

    $data = $this->db->query($sql);

    return (isset($data[0]['id'])) ? $data[0]['id'] : false;
  }

  /**
   * @return DiseaseModel
   */
  public function getOneByTitle($title)
  {
    $sql = 'SELECT *
          FROM ' . $this->table_name . '
          WHERE title = "' . $this->db->escape($title) . '"';
    $db = Register::get('db');
    $data = $db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  public function setContentById($content, $disease_id)
  {
    $sql = 'UPDATE ' . $this->table_name . '
          SET content = "' . $this->db->escape($content) . '"
          WHERE id = ' . $disease_id;

    Register::get('db')->query($sql);
  }

  public function setExtendedContentById($extended_content, $disease_id)
  {
    $sql = 'UPDATE ' . $this->table_name . '
          SET extended_content = "' . $this->db->escape($extended_content) . '"
          WHERE id = ' . $disease_id;

    Register::get('db')->query($sql);
  }

  public function setSourcesById($sources, $disease_id)
  {
    $sql = 'UPDATE ' . $this->table_name . '
          SET sources = "' . $this->db->escape($sources) . '"
          WHERE id = ' . $disease_id;

    Register::get('db')->query($sql);
  }

  public function setIsActiveById($is_active, $disease_id)
  {
    $sql = 'UPDATE ' . $this->table_name . '
          SET is_active = ' . (int)$is_active . '
          WHERE id = ' . $disease_id;

    Register::get('db')->query($sql);
  }

  public function setIsActive($is_active)
  {
    $sql = 'UPDATE ' . $this->table_name . '
          SET is_active = ' . (int)$is_active;

    Register::get('db')->query($sql);
  }

  public function getActiveOneById($disease_id)
  {
    $sql = 'SELECT *
          FROM ' . $this->table_name . '
          WHERE is_active = 1
          AND id = ' . $disease_id;
    $db = Register::get('db');
    $data = $db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  public function getDiseasesWithoutDescription()
  {
    $db = Register::get('db');

    $sql = 'SELECT *
          FROM ' . $this->table_name . '
          WHERE description IS NULL';

    $data = $db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  public function getOneByContentProjectId($content_project_id)
  {
    $sql = 'SELECT *
          FROM ' . $this->table_name . '
          WHERE content_project_id = ' . (int)$content_project_id;
    $db = Register::get('db');
    $data = $db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  public function getActiveListWithLimitForYandex($limit)
  {
    $db = Register::get('db');

    $sql = 'SELECT *
          FROM ' . $this->table_name . ' d
          WHERE is_active = 1
          AND (date_yandex_send is null OR date_yandex_send <= date_update)
          ORDER BY date_yandex_send ASC
          LIMIT 0,' . $limit;

    $data = $db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  public function setDateUpdateById($dt_edit, $disease_id)
  {
    $sql = 'UPDATE ' . $this->table_name . '
          SET date_update = "' . $this->db->escape($dt_edit) . '"
          WHERE id = ' . $disease_id;

    Register::get('db')->query($sql);
  }

  public function getCountActiveList()
  {
    $db = Register::get('db');

    $sql = 'SELECT count(*) as result
          FROM ' . $this->table_name . '
          WHERE is_active = 1';

    $data = $db->query($sql);

    return $data[0]['result'];
  }

  public function getCountNotInYandex()
  {
    $db = Register::get('db');

    $sql = 'SELECT count(*) as result
          FROM ' . $this->table_name . '
          WHERE date_yandex_send is null
          AND is_active = 1';

    $data = $db->query($sql);

    return $data[0]['result'];
  }

  public function getCountInYandex()
  {
    $db = Register::get('db');

    $sql = 'SELECT count(*) as result
          FROM ' . $this->table_name . '
          WHERE date_yandex_send is not null
          AND is_active = 1';

    $data = $db->query($sql);

    return $data[0]['result'];
  }

  public function getCountInYandexToUpdate()
  {
    $db = Register::get('db');

    $sql = 'SELECT count(*) as result
          FROM ' . $this->table_name . '
          WHERE date_yandex_send is not null
          AND date_update > date_yandex_send
          AND is_active = 1';

    $data = $db->query($sql);

    return $data[0]['result'];
  }

  /**
   * @param ModelSearchCriteria $criteria
   *
   * @return DiseaseModel[]
   */
  public function getListByModelSearchCriteria(ModelSearchCriteria $criteria)
  {
    $search_api = new ElasticSearchDiseaseIndexControl();
    try {
      $ids = $search_api->search($criteria);
    }catch(Exception $e){
      $ids = [];
    }

    return $this->getListByIds($ids);
  }

  public function getSimilarDisease($disease_id)
  {
    $db = Register::get('db');
    $similarDisease = array();

    $sql = 'SELECT *
            FROM disease
            WHERE `id` IN (
              SELECT DISTINCT std.disease_id
                FROM `specialty_to_disease` AS std RIGHT JOIN (
                  SELECT DISTINCT std.specialty_id AS specialty_id
                    FROM `disease` AS d
                      INNER JOIN `specialty_to_disease` AS std ON std.disease_id = d.id
                      INNER JOIN `specialty` AS s ON s.id = std.specialty_id
                    WHERE d.%s
                      AND d.`is_active` = 1
                      AND std.main_flag = 1
                      AND s.parent_id IS NULL
                      OR s.parent_id = 0
                    ORDER BY std.main_flag DESC ) AS mt ON std.specialty_id = mt.specialty_id
                  ORDER BY std.main_flag DESC
            )
            ORDER BY RAND() LIMIT 6';


    if (gettype($disease_id) == 'string') $sql = sprintf($sql, '`alias` = \'' . $disease_id . '\'');
    elseif (gettype($disease_id) == 'integer') $sql = sprintf($sql, '`id` = \'' . $disease_id . '\'');
    else return $similarDisease;

    $similarDisease = $db->query($sql);

    if (count($similarDisease)) {
      foreach ($similarDisease AS $sdKey => $sdValue)
        $similarDisease[$sdKey]['link'] = SITE_SCHEME . '://' . $_SERVER['HTTP_HOST'] . '/disease/' . $sdValue['alias'];
    } else $similarDisease = array();


    return $similarDisease;
  }
}
