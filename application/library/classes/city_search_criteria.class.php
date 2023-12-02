<?php
	class CitySearchCriteria extends ModelSearchCriteria
	{
		public $has_doctors;
		public $has_clinics;
		public $has_laboratories;
        public $has_one;

		public $name;
	}