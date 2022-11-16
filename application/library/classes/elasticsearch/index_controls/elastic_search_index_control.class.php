<?php

/**
 * Interface IElasticSearchModelManager
 *
 * Абстрактный класс для классов, которые умеют управлять данными данной модели в индексе:
 * добавлять, обновлять, искать
 */
class ElasticSearchIndexControl implements IElasticSearchIndexControl
{
  /**
   * @var \Elastica\Client
   */
  protected $elastica_api;

  public function __construct()
  {
    $this->elastica_api = ElasticaFactory::getApi();
  }

  /**
   * @return \Elastica\Client
   */
  protected function getElasticaApi()
  {
    return $this->elastica_api;
  }

  /**
   * Создание нового индекса
   *
   * @param $index_name
   *
   * @return mixed
   */
  public function createIndex($index_name)
  {
    $index = $this->getIndex($index_name);
    $index->create(array(
      'number_of_shards'   => 4,
      'number_of_replicas' => 0,
      'analysis'           => array(
        'analyzer' => array(
          'indexAnalyzer'  => array(
            'type'      => 'custom',
            'tokenizer' => 'standard',
            'filter'    => array('lowercase', 'russian_morphology', 'tags_filter'),
          ),
          'searchAnalyzer' => array(
            'type'      => 'custom',
            'tokenizer' => 'standard',
            'filter'    => array('lowercase', 'russian_morphology', 'tags_filter'),
          ),
          'autocomplete'   => array(
            'type'      => 'custom',
            'tokenizer' => 'standard',
            'filter'    => array('lowercase', 'russian_morphology', 'tags_filter'),
          ),
        ),
        'filter'   => array(
          'mynGram'     => array(
            "type"     => "edgeNGram",
            "min_gram" => 3,
            "max_gram" => 30
          ),
          "tags_filter" => array(
            "type"       => "word_delimiter",
            "type_table" => array("-" => "ALPHA"),
          ),
        ),
      ),
    ),
      true /* удаление существующего индекса true, оставляем - false */
    );
  }

  /**
   * Получение индекса
   *
   * @param $index_name
   *
   * @return \Elastica\Index
   */
  public function getIndex($index_name)
  {
    return $this->getElasticaApi()->getIndex($index_name);
  }

  /**
   * Удаление индекса
   *
   * @param $index_name
   *
   * @return mixed
   */
  public function deleteIndex($index_name)
  {
    $this->getIndex($index_name)->delete();
  }

  /**
   * Обновление индекса
   *
   * @param string $index_name название индекса
   *
   * @return mixed
   */
  public function refreshIndex($index_name)
  {
    $this->getIndex($index_name)->refresh();
  }

    /**
     * @param string $oldIndexName
     * @param string $newIndexName
     */
    public function swapIndexWithAlias($oldIndexName, $newIndexName)
    {
        $data = [
            'actions' => [
                ['add' => ['index' => $newIndexName, 'alias' => $oldIndexName]],
                ['remove_index' => ['index' => $oldIndexName]],
            ],
        ];
        $endpoint = new \Elasticsearch\Endpoints\Indices\Aliases\Update();
        $endpoint->setBody($data);

        $response = $this->elastica_api->requestEndpoint($endpoint);
    }

    /**
     * @param string $alias
     * @param string $newIndexName
     */
    public function swapAliasIndices($alias, $newIndexName)
    {
        $data = [
            'actions' => [
                ['remove' => ['index' => '*', 'alias' => $alias]],
                ['add' => ['index' => $newIndexName, 'alias' => $alias]],
            ],
        ];
        $endpoint = new \Elasticsearch\Endpoints\Indices\Aliases\Update();
        $endpoint->setBody($data);

        $response = $this->elastica_api->requestEndpoint($endpoint);
    }

    /**
     * @param string $alias
     * @return \Elastica\Index[]
     */
    public function getIndicesWithAlias($alias)
    {
        $status = new \Elastica\Status($this->elastica_api);

        return $status->getIndicesWithAlias($alias);
    }

    /**
     * @param string $oldIndexName
     * @param string $newIndexName
     * @return \Elastica\Index
     */
    public function reindex($oldIndexName, $newIndexName)
    {
        $reindex = new \Elastica\Reindex(
            $this->getIndex($oldIndexName),
            $this->getIndex($newIndexName)
        );

        return $reindex->run();
    }

    /**
     * @param string $indexName
     * @return bool
     */
    public function aliasExists($indexName)
    {
        $status = new \Elastica\Status($this->elastica_api);

        return $status->aliasExists($indexName);
    }
}
