<?php
	class CheckboxFilterType extends FilterType
	{
		public function __construct($field_name, $settings)
		{
			$this->field_name = $field_name;
			$this->settings = $settings;
		}

		public function getView($val)
		{
			$checked = $val ? 'checked="checked"' : '';
			$html = '<input type="checkbox" id="'.$this->getElementName().'" '.$checked.'" name="'.$this->getElementName().'" class="filter-element filter-checkbox" />';
			return $html;
		}
	}