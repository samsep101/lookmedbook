<?php
  class ModelManagerCacheDecorator
  {
    /**
     * @var ICachedModelManager|ModelManager
     */
    public $decorated_manager;

    /**
     * @var IMemoryCache
     */
    private $memory_cache;

    public function __construct(ICachedModelManager $decorated_manager)
    {
      $this->decorated_manager = $decorated_manager;
      $this->memory_cache = new MemcacheMemoryCacheAdapter();
    }


    public function __call($method_name, $arguments)
    {
      $cached_methods = $this->decorated_manager->getCachedMethods();
      if ($this->memory_cache && (strpos($method_name, 'get') !== false) && in_array($method_name, $cached_methods))
      {
        $key = $this->getKeyByMethodNameAndArguments($method_name, $arguments);

        $cached_data = $this->memory_cache->getValue($key);

        if ($cached_data)
        {
          if ($cached_data['type'] == 'simple')
          {
            $cached_data = $cached_data['data'];
          } elseif($cached_data['type'] == 'list') {
            if ($cached_data['data'])
              $cached_data = $this->decorated_manager->getSortedListByIdList($cached_data['data']);
            else
              return array();
          } else {
            throw new Exception('Некорректный формат данных из кеша');
          }
          return $cached_data;
        } else {
          $callback = array($this->decorated_manager, $method_name);

          if (is_callable($callback))
          {
            $data = call_user_func_array($callback, $arguments);

            if(is_array($data))
            {
              /**
               * @var DynamicModel[] $data
               */
              $cached_ids = array();
              if($data)
                foreach($data as $row)
                {
                  $cached_ids[] = $row->getId();
                }

              $cached_data = array(
                'type' => 'list',
                'data' => $cached_ids
              );
            } else {
              $cached_data = array(
                'type' => 'simple',
                'data' => $data
              );
            }
            $this->memory_cache->setValue($key, $cached_data, time() + 24*60*60, $this->decorated_manager->getGroupName());

            return $data;
          } else {
            throw new Exception('Неподдерживаемый метод');
          }
        }
      } else {
        if ($method_name == 'save')
        {
          if($arguments[0] instanceof DynamicModel)
          {
            $this->memory_cache->clearGroupCache($this->decorated_manager->getGroupName().':list');
            $this->memory_cache->clearGroupCache($this->decorated_manager->getGroupName().':'.$arguments[0]->getId());

            // Помечаем данные, как "необходимо обновить поисковый индекс"
            $class = get_class($arguments[0]);
            if(in_array($class, Register::get('index_models')))
            {
              $arguments[0]->is_need_to_index_update = 1;
            }
          }
        }

        // Данный блок служит для добавления методов, которые необходимо удалить
        // из поискового индекса
        if($method_name == 'deleteById')
        {
          $class = $this->decorated_manager->getModelName();

          if(in_array($class, Register::get('index_models')))
          {
            $table = $this->decorated_manager->getTableName();

            $model_index_delete = new ModelIndexDeleteModel();
            $model_index_delete->name = $table;
            $model_index_delete->model_id = $arguments[0];
            $model_index_delete->save();
          }
        }
        if($method_name == 'delete')
        {
          $class = $this->decorated_manager->getModelName();

          if(in_array($class, Register::get('index_models')))
          {
            $table = $this->decorated_manager->getTableName();

            $model_index_delete = new ModelIndexDeleteModel();
            $model_index_delete->name = $table;
            $model_index_delete->model_id = $arguments[0]->getId();
            $model_index_delete->save();
          }
        }

        $callback = array($this->decorated_manager, $method_name);
        return call_user_func_array($callback, $arguments);
      }
    }

    private function getKeyByMethodNameAndArguments($method_name, $arguments)
    {
      return md5(get_class($this->decorated_manager).$method_name.serialize($arguments));
    }
  }