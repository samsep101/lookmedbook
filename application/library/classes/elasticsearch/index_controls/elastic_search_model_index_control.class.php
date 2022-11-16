<?php

abstract class ElasticSearchModelIndexControl implements IElasticSearchModelIndexControl
{
  /**
   * @var IElasticSearchObjectFactory
   */
  protected $object_factory;
  /**
   * @var IElasticSearchIndexControl
   */
  protected $index_manager;
  /** @var \Elastica\Client */
  protected $elastica_api;
  /** @var int */
  protected $total_hits;
  /** @var bool */
  protected static $need_to_get_total_hits = false;
  /** @var string */
  private $indexName;

  public static function setNeedToGetTotalHits($value)
  {
    self::$need_to_get_total_hits = (bool)$value;
  }

  /**
   * @param string|null $indexName
   */
  public function __construct($indexName = null)
  {
      $this->indexName = $indexName ?: Register::get('ELASTIC_SEARCH_INDEX');
  }

  /**
   * @return \Elastica\Index
   */
  protected function getIndex()
  {
    return $this->getIndexManager()->getIndex($this->indexName);
  }

  protected function getIndexManager()
  {
    if (!$this->index_manager) {
      $this->index_manager = new ElasticSearchIndexControl();
    }

    return $this->index_manager;
  }

  /**
   * Получение типа, с которым работает данный менеджер
   *
   * @return \Elastica\Type
   */
  abstract protected function getType();

  /**
   * @return IElasticSearchObjectFactory
   */
  public function getObjectFactory()
  {
    return $this->object_factory;
  }

  /**
   * @return mixed
   */
  public function getTotalHits()
  {
    return $this->total_hits;
  }

  /**
   * @return \Elastica\Client
   */
  public function getElasticaApi()
  {
    if (!$this->elastica_api) {
      $this->elastica_api = ElasticaFactory::getApi();
    }

    return $this->elastica_api;
  }

  /**
   * Добавляет либо обновляет документы, которые необходимо проиндексировать
   *
   * @param array $documents
   *
   * @return mixed
   */
  public function addDocuments(array $documents)
  {
    $result = array();
    $formatter = $this->getObjectFactory()->getFormatter();

    foreach ($documents as $document) {
      $result[] = $formatter->toElasticSearchView($document);
    }

    $this->getType()->addDocuments($result);

    $this->getIndex()->refresh();

    return true;
  }

  /**
   * Добавляет в индекс один документ
   *
   * @param DynamicModel $document
   *
   * @return mixed
   */
  public function addDocument(DynamicModel $document)
  {
    $result = $this->getObjectFactory()->getFormatter()->toElasticSearchView($document);

    $this->getType()->addDocument($result);
  }

  /**
   * Очищает индекс документов
   *
   * @return bool
   */
  public function clearIndex()
  {
    $this->getType()->delete();
  }


  public function getTotalCount(ModelSearchCriteria $criteria)
  {
    $result_query = $this->buildQueryObject($criteria);

    try {
      $cnt = $this->getType()->count($result_query);
    } catch (Exception $e) {
      $cnt = 0;
    }
    return $cnt;
  }

  /**
   * Поиск данных согласно критериям поиска
   *
   * @param ModelSearchCriteria $criteria
   *
   * @return mixed
   */
  public function search(ModelSearchCriteria $criteria)
  {
    $result_query = $this->buildQueryObject($criteria);
    $data = $this->getType()->search($result_query);

    $this->total_hits = $data->getTotalHits();

    $result = array();
    foreach ($data as $v) {
      /**
       * @var \Elastica\Result $v
       */
      $result[] = $v->getId();
    }

    return $result;
  }

  /**
   * Построение объекта запроса по критериям поиска
   *
   * @param ModelSearchCriteria $criteria
   *
   * @return mixed
   */
  abstract protected function buildQueryObject(ModelSearchCriteria $criteria);

  protected function addPaging(ModelSearchCriteria $criteria, \Elastica\Query $query)
  {
    if ($criteria->page && $criteria->by_page) {
      $size = $criteria->by_page;

      if ($criteria->get_extra_item) {
        $query->setSize($size + 1);
      } else {
        $query->setSize($size);
      }

      $query->setFrom(($criteria->page - 1) * $criteria->by_page);
    } else {
      $query->setSize(10000);
      $query->setFrom(0);
    }
  }

  /**
   * Добавление мэппинга
   *
   * @return mixed
   */
  public function applyMapping()
  {
    $type = $this->getType();

    $mapping = new \Elastica\Type\Mapping();
    $mapping->setType($type);

    $mapper = $this->getObjectFactory()->getMapper();
    $mapping->setProperties($mapper->getFieldsMapping());

    $mapping->send();
  }


  /**
   * Удаление документа по идентификатору
   *
   * @param $id
   *
   * @return bool
   */
  public function deleteById($id)
  {
    $this->getType()->deleteById($id);

    return true;
  }

  /**
   * Удаление списка документов
   *
   * @param array $ids
   *
   * @return mixed
   */
  public function deleteByIds(array $ids)
  {
    $this->getType()->deleteIds($ids);
  }

  public function getDocumentsIds()
  {
    $result_query = new \Elastica\Query();
    $result_query->setSize(500000);
    $result_query->setFields(array('id'));

    $data = $this->getType()->search($result_query);

    $result = array();
    foreach ($data as $v) {
      $result[] = $v->getId();
    }

    return $result;
  }

}
