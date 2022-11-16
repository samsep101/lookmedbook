<?php
	class SelectFilterType extends FilterType
	{
		public function __construct($field_name, $settings)
		{
			$this->field_name = $field_name;
			$this->settings = $settings;
		}

		public function getView($val)
		{
			$manager_class_name = $this->settings['manager_class_name'];
			$manager = ModelManagerFactory::getByName($manager_class_name);

			$search_criteria_class_name = $this->settings['search_criteria_class_name'];
			/**
			 * @var ModelSearchCriteria $search_criteria
			 */
			$search_criteria = new $search_criteria_class_name();

			$option_field_name = $this->settings['option_field_name'];

			if(isset($this->settings['search_criteria']) && $this->settings['search_criteria'])
			{
				foreach($this->settings['search_criteria'] as $field_name =>$value)
				{
					$search_criteria->{$field_name} = $value;
				}
			}


			if(isset($this->settings['sort_by']))
			{
				$search_criteria->sort_by = $this->settings['sort_by'];
			}

			/**
			 * @var DynamicModel[] $models
			 */
			$models = $manager->getListByModelSearchCriteria($search_criteria);

			$html = '<select type="text" id="'.$this->getElementName().'" name="'.$this->getElementName().'" class="filter-element">';
			$html .= '<option value="0">-</option>';
			if(count($models))
			{
				foreach($models as $model)
				{
					$selected = ($val == $model->getId()) ? 'selected="selected"' : '';

					$html .= '<option value="'.$model->getId().'" '.$selected.'>'.$model->{$option_field_name}.'</option>';
				}
			}
			$html .= '</select>';

			return $html;
		}
	}