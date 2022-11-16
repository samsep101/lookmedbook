<?php

/**
 * Parent class for all application controllers
 */
class Controller
{
  /** @var View */
  public $view;
  public $request;
  public $action;
  public $controller;
  public $layout = NULL;

  protected $folder;
  private $isRendered = FALSE;

  public function __construct()
  {
    $this->request = new Request();
  }

  public function setFolder($folder)
  {
    $this->folder = $folder;
  }



  /**
   * Render template
   *
   * @param string $templateName template name, for example "mytpl","myfolder/mytpl"
   */
  public function render($templateName = '')
  {
    $this->beforeRender();
    // you could set layout by $this->view->setLayout();
    if (!empty($this->layout))
      $this->view->setLayout($this->layout);
    $templatePath = $this->getTemplatePath($templateName);

    $this->view->render($templatePath);

    $this->isRendered = TRUE;
  }

  public function renderInString($templateName)
  {
    ob_start();
    $this->beforeRender();
    // you could set layout by $this->view->setLayout();
    if (!empty($this->layout))
      $this->view->setLayout($this->layout);
    $templatePath = $this->getTemplatePath($templateName);
    $this->view->render($templatePath);

    $content = ob_get_contents();
    ob_end_clean();

    return $content;
  }

    public function renderBlockInString($templateName)
    {
        ob_start();
        $this->view->block($templateName);
        return ob_get_clean();
    }

  public function request($name, $defaulValue = NULL)
  {
    return $this->request->getParam($name, $defaulValue);
  }

  /**
   * Is any template already has been rendered by current action ?
   * @return bool is any template already has been rendered by current action
   */
  public function isRendered()
  {
    return $this->isRendered;
  }

  /**
   * Redirect to custom URL
   *
   * @param string $url URL
   */
  public function redirectUrl($url = '/')
  {
    RedirectManager::redirect($url);
    exit();
  }

  /**
   * Redirect to URL specifed by controller, action and params
   *
   * @param string $action
   * @param string $controller
   * @param string $params
   */
  public function redirect($action = 'index', $controller = 'index', $params = '')
  {
    if ($params)
      $params = '?' . $params;

    header('location: ' . Application::getHttpRoot() . $controller . '/' . $action . '/' . $params);
    die();
  }

  /**
   * Error 404 posting
   *
   */
  public function error404($exception = NULL)
  {
    if (debug) {
      echo '<html><head><style>';
      echo '
	* {
		font-family: Verdana, Tahoma, Arial;
		font-size: 13px;
	}
	
	body {
	}
	
	.error {
		width: 860px;
		text-align: left;
	}
	
	.error h1 {
		font-size: 18px;
		margin-left: 36px;
	}
	
	.error-num {
		float: left;
		width: 30px;
		background-color: #fff;
		padding: 3px;
		text-align: left;
	}
	
	.error-descr {
		float: left;
		background-color: #f5f5f5;
		margin-bottom: 10px;
		padding: 3px;
		width: 800px;
		text-align: left;
	}
	
	.error-descr span {
		font-weight: bold;
	}
	
	';
      echo '</style></head></body>';
      echo '<center><div class="error"><h1>' . $exception->getMessage() . '</h1>';
      foreach ($exception->getTrace() as $key => $item) {
        echo '<div class="error-item"><div class="error-num">' . ($key + 1) . '.</div>';
        echo '<div class="error-descr">';
        echo '<span>' . $item['class'] . ' ' . $item['type'] . ' ' . $item['function'] . ' ' . '( ' . join(', ', $item['args']) . ' )' . '</span><br>';
        echo $item['file'] . ' (Line: ' . $item['line'] . ')<br>';
        echo '</div><br clear="all" /></div>';
      }
      echo '</div></center>';
      echo '</body></html>';
    } else {
      header("location: " . SITE_SCHEME . "://" . $_SERVER['SERVER_NAME'] . "/error404");
    }
    die();
  }


  public function beforeRender()
  {

  }

  /**
   * Get path to template by template name
   *
   * @param string $templateName
   *
   * @return string template path
   */
  protected function getTemplatePath($templateName)
  {


    $folder = ($this->folder) ? $this->folder . '/' : '';

    $path = NULL;
    if (preg_match('/\//', $templateName)) {
      $path = Application::getTemplatesDir(TRUE) . '/' . $templateName;
    } elseif (strlen($templateName) != 0) {
      $path = Application::getTemplatesDir(TRUE) . '/' . $this->controller . '/' . $templateName;
    } else {
      $path = Application::getTemplatesDir(TRUE) . '/' . $folder . $this->controller . '/' . $this->action;
    }

    return $path;
  }
}
