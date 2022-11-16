<?php
	class DynamicModel
	{
		protected $id;
		protected $params;
		protected $validator;

		protected $validate = TRUE;

		protected $save_process_flag = FALSE;

		protected $manager;

		protected $is_new = true;

		protected $is_need_to_filter = true;
		protected $raw_fields = [];

		/**
		 * @var array описывает названия моделей для полей, которые не соответствуют правилам наименования
		 *	  в виде array('название поля' => 'имя модели')
		 */
		protected $fields_models;

		public function setParams($id, $params)
		{
			$this->is_new = false;
			$this->id = $id;
			$this->params = $params;
		}

		public function setValidator(ModelValidator $validator)
		{
			$this->validator = $validator;
		}

		/**
		 * @return ModelValidator
		 */
		public function getValidator()
		{
			return $this->validator;
		}

		public function getId()
		{
			return $this->id;
		}

		public function setId($id)
		{
			$this->id = $id;
			$this->params['id'] = $id;
		}

		public function isNeedToFilter()
		{
			return $this->is_need_to_filter;
		}

        public function isNeedToFilterField($field_name)
        {
            return !in_array($field_name, $this->raw_fields, true);
        }

		public function disableFilter()
		{
			$this->is_need_to_filter = false;
		}

		public function enableFilter()
		{
			$this->is_need_to_filter = true;
		}

		/**
		 * Получение значений полей, к которым до этого не обращались
		 *
		 * @param string $param_name название поля
		 *
		 * @return mixed значение поля
		 * @throws Exception
		 */
		public function __get($param_name)
		{
			if (isset($this->params[$param_name])) {
				if (isset($this->fields_models[$param_name])) {
					if ($this->params[$param_name]) {
						$model_manager = ModelManagerFactory::getByName($this->fields_models[$param_name]);
						$this->{$param_name} = $model_manager->getOneById($this->params[$param_name]);
					} else {
						$this->{$param_name} = FALSE;
					}
				} else {
					$this->{$param_name} = $this->params[$param_name];
				}

				return $this->{$param_name};
			}

			if (is_callable(array($this, '_field_' . $param_name))) {
				$this->{$param_name} = call_user_func_array(array($this, '_field_' . $param_name), array());
				return $this->{$param_name};
			}

			// todo: протестировать
			$param_name_id = $param_name.'_id';
			if(property_exists($this, $param_name_id) && $this->$param_name_id != null || isset($this->params[$param_name_id])) {

				// проверяем именно свойство, ибо isset существующего свойства вернет false через $this
				//if(strstr($param_name, 'docdoc')){
				//  debug(isset($this->{$param_name_id}), 1); // <--false
				//  debug($this->{$param_name_id});         // <-- NULL свойство пустое, но инициализировано со значением NULL
				//}
				if ( false === property_exists($this, $param_name_id)) {
					$this->{$param_name_id} = $this->params[$param_name_id];
				}

				//if (!isset($this->{$param_name_id}))
				//	$this->{$param_name_id} = $this->params[$param_name_id];

				if ($this->{$param_name_id})
				{
					if (class_exists($param_name.'Manager', FALSE) || Application::tryToLoadClass($param_name.'Manager'))
					{
						$model_manager = ModelManagerFactory::getByName($param_name);
						if($model_manager) {
						  $this->{$param_name} = $model_manager->getOneById($this->{$param_name_id});
						}else{
						  $this->{$param_name} = FALSE;
						}
					} else {
						$this->{$param_name} = FALSE;
					}
				} else {
					$this->{$param_name} = false;
				}

				return $this->{$param_name};
			}

			return NULL;
		}

		public function __isset($param_name)
        {
            if (property_exists($this, $param_name)) {
                return $this->$param_name != null;
            }
            if (isset($this->params[$param_name])) {
                return true;
            }

            try {
                $value = $this->__get($param_name);
            } catch (\Exception $e) {
                $value = null;
            }

            return $value != null;
        }

        public function validate()
		{
			if (!$this->validate)
				return TRUE;

			if (!$this->validator)
				$this->validator = ModelValidatorFactory::getValidatorByObject($this);

			return $this->validator->validate($this);
		}

		public function setFields($fields)
		{
			if (count($fields)) {
				foreach ($fields as $field_name => $field_value) {
					$this->{$field_name} = $field_value;
				}
			}
		}

		public function disableValidation()
		{
			$this->validate = FALSE;
		}

		public function enableValidation()
		{
			$this->validate = TRUE;
		}

		/**
		 * @return ModelManager
		 */
		public function getManager()
		{
			if (!$this->manager)
				$this->manager = ModelManagerFactory::getManagerByModel($this);

			return $this->manager;
		}

		public function getUniqueId()
		{
			return $this->getId();
		}

		public function getFields()
		{
			return $this->getManager()->getFields();
		}

		public function save()
		{
			return $this->getManager()->save($this);
		}


		public function getSaveProcessFlag()
		{
			return $this->save_process_flag;
		}

		public function setSaveProcessFlag()
		{
			$this->save_process_flag = TRUE;
		}

		public function unsetSaveProcessFlag()
		{
			$this->save_process_flag = false;
		}

		public function setDefaultValue($field_name, $default_value)
		{
			$this->params[$field_name] = $default_value;
		}

		public function clearParams()
		{
			$this->params = array();
		}

		public function isNew()
		{
			return $this->is_new;
		}

		public function delete()
		{
			$this->getManager()->deleteById($this->getId());
		}

        /**
         * Returns the old value of the named attribute
         * @param string $name the attribute name
         * @return mixed|null
         */
		public function getOldAttribute($name)
        {
            return isset($this->params[$name]) ? $this->params[$name] : null;
        }

        /**
         * Returns the old attribute values
         * @return array old attribute values (name-value array)
         */
        public function getOldAttributes()
        {
            return $this->params === null ? [] : $this->params;
        }

	}
