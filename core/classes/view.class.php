<?php

use app\library\resources\Style;

class View extends Dynamic
{

  public $sufix = 'ViewHelper';

  private $__values;

  private $__extension = '.tpl';

  private $__layout;

  private $__template;

  /**
   * @var ViewFilter[]
   */
  private $view_filters = array();
    private $styles = [];
    private $layoutParams = [];

    public function addViewFilter(ViewFilter $filter)
  {
    $this->view_filters[] = $filter;
  }

  public function __construct()
  {
    $this->__values = array();
  }

  public function __set($varName, $value)
  {
    $this->__values[$varName] = $value;
  }

  public function __get($varName)
  {
    if (isset($this->__values[$varName]))
      return $this->__values[$varName];
    else
      return NULL;
  }

  public function render($templateName)
  {
    ob_start();
    extract($this->__values);
    $this->__template = $templateName . $this->__extension;
    if (is_file($this->__layout)) {
      include($this->__layout);
    } else {
      include($this->__template);
    }
    $html = ob_get_contents();
    ob_end_clean();

    echo $this->filter($html);
  }

    /**
     * Рендерим отображение из папки {application}/{templates}/_name_.tpl
     * - второй параметр позволяет изключить создание переменных в этой области видимости (по сути дублируются)
     * @param string $templateName
     * @param bool $extractValues
     * @return string
     */
    public function renderInString($templateName, $extractValues = true)
    {
        ob_start();
        $extractValues AND extract($this->__values);
        $this->__template = Application::getTemplatesDir(TRUE) . '/' . $templateName . $this->__extension;
        include($this->__template);
        $html = ob_get_contents();
        ob_end_clean();

        return $this->filter($html);
    }

    public function block($templateName, $params = null)
    {
        if (0 && debug == 1) {
            echo "<!--" . $templateName . "-->";
        }

        extract($this->__values);
        if ($params) {
            extract($params);
        }
        $file = Application::getTemplatesDir(true) . '/' . $templateName . $this->__extension;
        include($file);
    }

    public function tryBlock($templateName, $params = null)
    {
        $file = Application::getTemplatesDir(true) . '/' . $templateName . $this->__extension;
        if (file_exists($file)) {

            extract($this->__values);
            if ($params) {
                extract($params);
            }

            include($file);
        }

    }

  public function content()
  {
    extract($this->__values);
    if(file_exists($this->__template)) {
      include($this->__template);
    }
  }

  public function clear()
  {
    $this->__values = array();
  }

  public function setLayout($templatePath)
  {
    $this->__layout = Application::getTemplatesDir(TRUE) . '/' . $templatePath . $this->__extension;
  }

  public function getLayout()
  {
    return $this->__layout;
  }

  public function getExtension()
  {
      return $this->__extension;
  }

  protected function filter($html)
  {
    if ($this->view_filters) {
      foreach ($this->view_filters as $filter) {
        $html = $filter->filter($html);
      }
    }

    return $html;
  }

    public function registerStyle(Style $style)
    {
        $this->styles[$style->getType()][] = $style;
    }

    /**
     * @param string $type
     *
     * @return Style[]
     */
    public function getStyles($type)
    {
        if (empty($this->styles[$type])) {
            return [];
        }

        return $this->styles[$type];
    }

    public function setLayoutParam($key, $value)
    {
        $this->layoutParams[$key] = $value;
    }

    public function getLayoutParam($key, $defaultValue = null)
    {
        if (isset($this->layoutParams[$key])) {
            return $this->layoutParams[$key];
        }

        return $defaultValue;
    }
}
