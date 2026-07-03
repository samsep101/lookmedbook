<?php

class ElasticaController extends BaseController
{
  /**
   * @var Elastica\Client
   */
  private $elastica_client = array();

  public function __construct()
  {
    $this->elastica_client = ElasticaFactory::getApi();
  }

  public function beforeAction()
  {
    set_time_limit(0);
    ModelManager::disableEntityMapGlobal();
  }

  public function createIndexes()
  {
      $this->createSearchCache();
      $this->createSearchIndex();
  }

    /**
     * @param bool $updateData [default: true]
     * @param bool $deleteOldIndices [default: true]
     */
    public function migrate($updateData = null, $deleteOldIndices = null)
    {
        $updateData = $updateData !== null ? $updateData : $this->getArg(2, true);
        $deleteOldIndices = $deleteOldIndices !== null ? $deleteOldIndices : $this->getArg(3, true);
        $oldIndexName = Register::get('ELASTIC_SEARCH_INDEX');
        $newIndexName = $oldIndexName . time();
        $this->createSearchIndex($newIndexName);
        $this->applyMapping($newIndexName);
        $this->reindex($oldIndexName, $newIndexName);
        if ($updateData) {
            $this->reIndexAll($newIndexName);
        }
        if ($this->isIndexAnAlias($oldIndexName)) {
            $this->swapAliasIndices($oldIndexName, $newIndexName, $deleteOldIndices);
        } else {
            $this->swapIndexWithAlias($oldIndexName, $newIndexName);
        }
    }

    /**
     * @param string $indexName
     * @return bool
     */
    public function isIndexAnAlias($indexName = null)
    {
        $print = $indexName === null;
        $indexName = $indexName ?: $this->getArg(2, Register::get('ELASTIC_SEARCH_INDEX'));
        $esic = new ElasticSearchIndexControl();
        $isAlias = $esic->aliasExists($indexName);
        $print and printf('Is %s an alias? %d' . PHP_EOL, $indexName, $isAlias);
        return $isAlias;
    }

    /**
     * @param string $oldIndexName
     * @param string $newIndexName
     */
    public function reindex($oldIndexName = null, $newIndexName = null)
    {
        $oldIndexName = $oldIndexName ?: $this->getArg(2);
        $newIndexName = $newIndexName ?: $this->getArg(3);
        if (empty($oldIndexName) || empty($newIndexName)) {
            throw new InvalidArgumentException('Index names must be provided');
        }
        $esic = new ElasticSearchIndexControl();
        $newIndex = $esic->reindex($oldIndexName, $newIndexName);
    }

    /**
     * @param string $oldIndexName
     * @param string $newIndexName
     */
    public function swapIndexWithAlias($oldIndexName = null, $newIndexName = null)
    {
        $oldIndexName = $oldIndexName ?: $this->getArg(2);
        $newIndexName = $newIndexName ?: $this->getArg(3);
        if (empty($oldIndexName) || empty($newIndexName)) {
            throw new InvalidArgumentException('Index names must be provided');
        }
        $esic = new ElasticSearchIndexControl();
        $esic->swapIndexWithAlias($oldIndexName, $newIndexName);
    }

    /**
     * @param string $alias
     * @param string $newIndexName
     * @param bool $deleteOldIndices [default: true]
     */
    public function swapAliasIndices($alias = null, $newIndexName = null, $deleteOldIndices = null)
    {
        $alias = $alias ?: $this->getArg(2);
        $newIndexName = $newIndexName ?: $this->getArg(3);
        $deleteOldIndices = $deleteOldIndices !== null ? $deleteOldIndices : $this->getArg(4, true);
        if (empty($alias) || empty($newIndexName)) {
            throw new InvalidArgumentException('Index names must be provided');
        }
        $esic = new ElasticSearchIndexControl();
        if ($deleteOldIndices) {
            $oldIndices = $esic->getIndicesWithAlias($alias);
        }
        $esic->swapAliasIndices($alias, $newIndexName);
        if ($deleteOldIndices) {
            foreach ($oldIndices as $index) {
                $index->delete();
            }
        }
    }

    /**
     * @param int $pos
     * @param mixed $default
     * @return mixed
     */
    public function getArg($pos, $default = null)
    {
        return isset($_SERVER['argv'][$pos]) ? $_SERVER['argv'][$pos] : $default;
    }

    /**
     * Создание поискового индекса
     * @param string|null $indexName
     * @throws \Exception
     */
  public function createSearchIndex($indexName = null)
  {
      $indexName = $indexName ?: $this->getArg(2, Register::get('ELASTIC_SEARCH_INDEX'));
      $manager = new ElasticSearchIndexControl();
      $manager->createIndex($indexName);
  }

    /**
     * Создание псевдокеша на эластике
     * @throws Exception
     */
  public function createSearchCache()
  {
    $manager = new ElasticSearchIndexControl();

    if ($manager->getIndex(Register::get('ELASTIC_SEARCH_CACHE'))->exists()) {
      $manager->deleteIndex(Register::get('ELASTIC_SEARCH_CACHE'));
    }
    $manager->createIndex(Register::get('ELASTIC_SEARCH_CACHE'));
  }

  /**
   * @param string|null $indexName
   */
  public function applyMapping($indexName = null)
  {
    $indexName = $indexName ?: $this->getArg(2, Register::get('ELASTIC_SEARCH_INDEX'));

    $elastic_doctor_manager = new ElasticSearchDoctorIndexControl($indexName);
    $elastic_doctor_manager->applyMapping();

    $elastic_clinic_manager = new ElasticSearchClinicIndexControl($indexName);
    $elastic_clinic_manager->applyMapping();

    $elastic_disease_manager = new ElasticSearchDiseaseIndexControl($indexName);
    $elastic_disease_manager->applyMapping();

    $elastic_product_manager = new ElasticSearchProductIndexControl($indexName);
    $elastic_product_manager->applyMapping();

    $elastic_laboratory_manager = new ElasticSearchLaboratoryIndexControl($indexName);
    $elastic_laboratory_manager->applyMapping();
  }

  public function applySearchCacheMapping()
  {
      $elastic_service_price_manager = new ElasticSearchServicePriceIndexControl();
      $elastic_service_price_manager->applyMapping();
  }

  public function addDocuments()
  {
    set_time_limit(0);
    $doctor_index_command = new DoctorIndexCommand();
    $doctor_index_command->processNotIndexedDocuments();

    $clinic_index_command = new ClinicIndexCommand();
    $clinic_index_command->processNotIndexedDocuments();

    $disease_index_command = new DiseaseIndexCommand();
    $disease_index_command->processNotIndexedDocuments();

    $product_index_command = new ProductIndexCommand();
    $product_index_command->processNotIndexedDocuments();

    $laboratory_index_command = new LaboratoryIndexCommand();
    $laboratory_index_command->processNotIndexedDocuments();
  }

  public function addDoctorDocument()
  {
    set_time_limit(0);
    $doctor_index_command = new DoctorIndexCommand();
    $doctor_index_command->processNotIndexedDocuments();
  }

  public function deleteDocuments()
  {
    set_time_limit(0);
    $doctor_index_command = new DoctorIndexCommand();
    $doctor_index_command->processDeletedDocuments();

    $clinic_index_command = new ClinicIndexCommand();
    $clinic_index_command->processDeletedDocuments();

    $disease_index_command = new DiseaseIndexCommand();
    $disease_index_command->processDeletedDocuments();

    $product_index_command = new ProductIndexCommand();
    $product_index_command->processDeletedDocuments();

    $laboratory_index_command = new ProductIndexCommand();
    $laboratory_index_command->processDeletedDocuments();
  }

  public function deleteAllDocuments()
  {
    set_time_limit(0);
    $doctor_index_command = new DoctorIndexCommand();
    $doctor_index_command->deletedAllDocuments();

    $clinic_index_command = new ClinicIndexCommand();
    $clinic_index_command->deletedAllDocuments();

    $disease_index_command = new DiseaseIndexCommand();
    $disease_index_command->deletedAllDocuments();

    $product_index_command = new ProductIndexCommand();
    $product_index_command->deletedAllDocuments();

    $laboratory_index_command = new ProductIndexCommand();
    $laboratory_index_command->deletedAllDocuments();
  }

  public function deleteLaboratoryDocuments()
  {
    set_time_limit(0);
    $laboratory_index_command = new ProductIndexCommand();
    $laboratory_index_command->deletedAllDocuments();
  }


  public function deleteProductDocuments()
  {
    set_time_limit(0);
    $product_index_command = new ProductIndexCommand();
    $product_index_command->deletedAllDocuments();
  }

  public function deleteDiseaseDocuments()
  {
    set_time_limit(0);
    $disease_index_command = new DiseaseIndexCommand();
    $disease_index_command->deletedAllDocuments();
  }

  public function deleteClinicDocuments()
  {
    set_time_limit(0);
    ini_set('memory_limit', '512M');
    $clinic_index_command = new ClinicIndexCommand();
    $clinic_index_command->deletedAllDocuments();
  }

  public function deleteDoctorDocuments()
  {
    set_time_limit(0);
    $doctor_index_command = new DoctorIndexCommand();
    $doctor_index_command->deletedAllDocuments();
  }

  /**
   * @param string|null $indexName
   */
  public function reIndexAll($indexName = null)
  {
    $indexName = $indexName ?: $this->getArg(2, Register::get('ELASTIC_SEARCH_INDEX'));

    set_time_limit(0);
    ini_set('max_execution_time','0');
    ini_set('memory_limit', '2048M');

    ModelManager::disableEntityMapGlobal();

    $doctor_index_command = new DoctorIndexCommand($indexName);
    $doctor_index_command->reIndexAll();

    $clinic_index_command = new ClinicIndexCommand($indexName);
    $clinic_index_command->reIndexAll();

    $disease_index_command = new DiseaseIndexCommand($indexName);
    $disease_index_command->reIndexAll();

    $product_index_command = new ProductIndexCommand($indexName);
    $product_index_command->reIndexAll();

    $laboratory_index_command = new LaboratoryIndexCommand($indexName);
    $laboratory_index_command->reIndexAll();
  }

  public function reindexDoctors()
  {
    ini_set('memory_limit', '512M');

    ModelManager::disableEntityMapGlobal();

    $doctor_index_command = new DoctorIndexCommand();
    $doctor_index_command->reIndexAll();
  }

  public function reindexSingleDoctor()
  {
      ini_set('memory_limit', '512M');
      ModelManager::disableEntityMapGlobal();

      // IDs for Golubinskaya (Irina and Olga)
      $ids = [229132, 148101];
      $idString = implode(',', $ids);

      // Force mark them for indexing
      $db = Register::get('db');
      $db->query("UPDATE doctor SET is_need_to_index_update = 0");
      $db->query("UPDATE doctor SET is_need_to_index_update = 1 WHERE id IN ($idString)");

      $doctor_index_command = new DoctorIndexCommand();
      $doctor_index_command->processNotIndexedDocuments();

      echo "Doctors " . $idString . " indexed successfully using processNotIndexedDocuments.\n";
  }

  public function reindexDiseases()
  {
    $disease_index_command = new DiseaseIndexCommand();
    $disease_index_command->reIndexAll();
  }

  public function reindexProducts()
  {
    $product_index_command = new ProductIndexCommand();
    $product_index_command->reIndexAll();
  }

  public function reindexClinics()
  {
    ini_set('memory_limit', '512M');
    ModelManager::disableEntityMapGlobal();
    $clinic_index_command = new ClinicIndexCommand();
    $clinic_index_command->reIndexAll();
  }

  public function reindexLaboratories()
  {
    ini_set('memory_limit', '512M');
    ModelManager::disableEntityMapGlobal();
    $laboratory_index_command = new LaboratoryIndexCommand();
    $laboratory_index_command->reIndexAll();
  }

  public function beforeRender()
  {
    exit();
  }

  public function reindexServicePrices()
  {
      // get prices from services. it's question:
      $service_price_index_command = new ServicePriceIndexCommand();
      $service_price_index_command->reIndexAll();
  }
}
