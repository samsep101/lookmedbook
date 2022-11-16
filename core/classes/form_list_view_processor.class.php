<?php
	class FormListViewProcessor {

		private $model_list;
		private $config_name;
		private $view_template;

		public function __construct($config_name, array $model_list)
		{
			$this->model_list = $model_list;

			$this->config_name = $config_name;
		}

		public function setViewTemplate($view_template)
		{
			$this->view_template = $view_template;
		}

		public function getView()
		{
			$html = '';
			if ($this->model_list)
			{
                $model_counter = 1;
				foreach($this->model_list as $model)
				{
					$view = new View();
					$view->view_processor = new FormViewProcessor($this->config_name, $model);
					$view->view_processor->setIsListElement(true);
                    $view->model = $model;

                    if ($this->config_name == 'feature_to_clinic') {
                        if ($model_counter % 3 == 1) {
                            $html .='<div class=" flo">';
                        }
                    }
					$html .= $view->renderInString($this->view_template);

                    if ($this->config_name == 'feature_to_clinic') {
                        if ($model_counter % 3 == 0) {
                            $html .='</div>';
                        }
                    }
                    $model_counter ++;
				}
			} else {
				$model = new DynamicModel();
				$view = new View();
				$view->view_processor = new FormViewProcessor($this->config_name, $model);
				$view->view_processor->setIsListElement(true);
                $view->model = $model;
				$html .= $view->renderInString($this->view_template);
			}
			return $html;
		}

		public function getTemplate()
		{
			$model = new DynamicModel();
			$view = new View();

			$view->view_processor = new FormViewProcessor($this->config_name, $model);
			$view->view_processor->setIsListElement(true);
			return $view->renderInString($this->view_template);
		}
	}