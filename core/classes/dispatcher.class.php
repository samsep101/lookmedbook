<?php

class Dispatcher
{

  private $defaultController = 'index';
  private $defaultAction = 'index';

  private $notFoundController = NULL;

  /**
   * Call controller specifed by URI
   *
   * @param string $uri request URI
   */
  public function process($uri = NULL)
  {
    // prepare URI
    $profiler = ProfilerFactory::getProfiler();
    $profiler->startTime('page');
    $profiler->setPage(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');

    $uri = $this->prepareUri($uri);

    $actionInformation = $this->dispatch($uri);

    $profiler->setController($actionInformation['controller']);
    $profiler->setAction($actionInformation['action']);

    $folder = isset($actionInformation['folder']) ? $actionInformation['folder'] : '';

    $default_controllers = Register::get('default_controllers');
    if (isset($default_controllers[$folder])) {
      $this->setNotFoundController($default_controllers[$folder]);
    }

    // load controller
    $className = $this->loadController($actionInformation['controller'], $folder);
    $class = new $className;
    $class->action = $actionInformation['action'];
    $class->controller = $actionInformation['controller'];

    $decorators = Register::get('controller_decorators');

    if ($decorators) {
      foreach ($decorators as $decorator) {
        if ($class instanceof $decorator['instanceof']) {
          /**
           * @var IDecorator $decorator_controller
           */
          $decorator_controller = new $decorator['decorator_class_name']();
          $decorator_controller->setDecoratedObject($class);

          $class = $decorator_controller;
        }
      }
    }
    /**
     * @var Controller $class
     */
    $class->setFolder($folder);
    $class->request = new Request($actionInformation['external_request_params']);
    $class->view = new View();
    // добавляем фильтр замены ссылок
    $class->view->addViewFilter(new LinkViewFilter());

    if (is_callable(array($class, $actionInformation['action']))) {
      if (is_callable(array($class, 'beforeAction')))
        call_user_func(array($class, 'beforeAction'));

      call_user_func_array(array($class, $actionInformation['action']), array());
      $isRendered = @call_user_func_array(array($class, 'isRendered'), array());

      if (!$isRendered)
        call_user_func_array(array($class, 'render'), array());
      if (is_callable(array($class, 'afterAction')))
        call_user_func(array($class, 'afterAction'));


    } else {
      throw new Exception('Undefined action "' . $actionInformation['action'] . '"');
    }

    $profiler->stopTime('page');
    $profiler->logdata();
  }

  public function setNotFoundController($controller)
  {
    $this->notFoundController = $controller;
  }

  /**
   * Prepare URI for parsing
   * 1. remove GET-params
   * 2. remove tralling slash
   * 3. remove base http path
   *
   * @param string $uri
   *
   * @return string prepared URI
   */
  private function prepareUri($uri = NULL)
  {
    if (empty($uri))
      $uri = $_SERVER['REQUEST_URI'];
    // get base http path
    $basePath = Application::getHttpRoot();
    // skip tralling slash in base http path
    $basePath = preg_replace('#/$#is', '', $basePath);
    // skip base http path
    $uri = preg_replace('#^' . $basePath . '#', '', $uri);
    // skip GET params
    $uri = preg_replace('/\?.*?$/', '', $uri);
    // skip tralling slash
    $uri = preg_replace('#/$#is', '', $uri);

    return $uri;
  }

  /**
   * Load controller file by controller name
   *
   * @param string $controller
   *
   * @return string controller class name
   */
  private function loadController($controller, $folder = '')
  {
    $controllerClassName = $controller . StringHelper::toCamelCase($folder) . 'Controller';

    $path = Application::getControllerPath($controller, $folder);

    if (empty($path)) {
      // for generator
      $path = Application::getClassDir($this->notFoundController);
      $controllerClassName = $this->notFoundController;
    }
    if (!empty($path)) {
      include_once($path);
      return $controllerClassName;
    } else {
      throw new Exception('Counld\'t load controller ' . $controller);
    }
  }

  /**
   * Parse URI
   * Return array like:
   * array(
   *         'controller' => 'controller-name',
   *         'action' => 'action-name',
   *         'external_request_params' => array('param1'=>'value1' ,'param2'=>'value2')
   * );
   *
   * @param string $uri
   *
   * @return array
   */
  private function dispatch($uri)
  {
    $result = $this->mapDispatch($uri);
    // if path not defined in map configuration
    if (empty($result)) {
      // try to parse url manually,
      // for example:
      // if uri look like /article/list/page/2/order/name
      // then this parsing should return
      // * controller - article
      // * action - list
      // * external_request_params - array('page' => 2, 'order'=> 'name')
      $uriData = explode('/', $uri);

      $controller_folders = Register::get('controller_folders');
      $controller_uri_index = 1;
      $controller_folder = '';
      if (isset($uriData[1]) && in_array(strtolower($uriData[1]), $controller_folders)) {
        $controller_folder = strtolower($uriData[1]);
        $controller_uri_index = 2;
      }


      $controller = (!empty($uriData[$controller_uri_index])) ? $uriData[$controller_uri_index] : $this->defaultController;

      $action_uri_index = $controller_uri_index + 1;

      $action = (!empty($uriData[$action_uri_index])) ? $uriData[$action_uri_index] : $this->defaultAction;

      $externalRequestParams = NULL;
      if (count($uriData) > $action_uri_index + 1) {
        for ($i = $action_uri_index + 1; $i < count($uriData); $i = $i + 2) {
          if (!empty($uriData[$i + 1])) {
            $externalRequestParams[$uriData[$i]] = $uriData[$i + 1];
          }
        }
      }

      $result = array(
        'folder' => $controller_folder,
        'controller' => $controller,
        'action' => $action,
        'external_request_params' => $externalRequestParams,
      );
    }
    return $result;
  }

  /**
   * Try to find URI  in map config and parse it useing rule specifed by map
   * Return array like:
   * array(
   *         'controller' => 'controller-name',
   *         'action' => 'action-name',
   *         'external_request_params' => array('param1'=>'value1' ,'param2'=>'value2')
   * );
   *
   * @param string $uri
   *
   * @return array
   */
  private function mapDispatch($uri)
  {
    $map = Register::get('map');
    $controller = NULL;
    $action = NULL;
    $mapRequestParams = NULL;
    foreach ($map as $mapData) {
      $mapUrl = preg_replace('/(\:\w+)/', '([^\/]+?)', $mapData['url']);
      // if match map
      if (preg_match('#^' . $mapUrl . '$#', $uri, $aMatches)) {
        $controller = $mapData['controller'];
        $action = $mapData['action'];
        $folder = isset($mapData['folder']) ? $mapData['folder'] : '';
        // bind defined in map request params
        if (preg_match_all('/:(\w+)/', $mapData['url'], $aId)) {
          unset($aId[0]);
          $mapRequestParams = array();
          foreach ($aId[1] as $key => $id) {
            $mapRequestParams[$id] = $aMatches[$key + 1];
          }
        }

        return array(
          'controller' => $controller,
          'action' => $action,
          'folder' => $folder,
          'external_request_params' => $mapRequestParams,
        );
      }
    }
    return NULL;
  }
}