<?php
	class ApiGenerator extends ApiController
	{
		public function __construct()
		{
			$this->access_validator = new ApiAccessValidator();

            $this->setAccessValidator(new ApiAccessValidator());
            $this->setResponse(new JsonApiResponse());

		}

		public function __call($method, $params)
		{
			$controller = $this->controller;
			$action = $this->action;

			$manager_class_name = $controller . 'Manager';

			if (Application::tryToLoadClass($manager_class_name)) {
				$manager = new $manager_class_name();
                echo $manager;
				if (is_callable(array($manager, $action))) {
					$method = new ReflectionMethod($manager_class_name, $action);
					$parameters = $method->getParameters();

					$params = array();

					if ($parameters) {
						foreach ($parameters as $parameter) {
							$params[] = $parameter->getName();
						}
					}


					$args = array();

					if ($params)
					{
						foreach ($params as $param)
						{
							$args[] = $this->request($param);
						}
					}

					$result = $method->invokeArgs($manager, $args);

                    $response_formatter = new ResponseFormatter();


                    $result = $response_formatter->format($result);

					echo $this->response->getResponse($result);
                    exit();
				}
			}

            // тут ошибка об неизвестном методе
			exit();
		}
	}