<?php
	class InputFilterType extends FilterType
	{
		public function __construct($field_name, $settings)
		{
			$this->field_name = $field_name;
			$this->settings = $settings;
		}

		public function getView($val)
		{
			$html = '<input type="text" id="'.$this->getElementName().'" value="'.$val.'" name="'.$this->getElementName().'" class="filter-element" />';
			return $html;
		}
	}