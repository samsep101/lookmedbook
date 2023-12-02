<?php
	class VisitSearchCriteria extends ModelSearchCriteria
	{
		public $visit_status_id = null;

		public $days_count_to_visit_min = null;
		public $days_count_to_visit_max = null;

		public $days_count_after_visit_min = null;
		public $days_count_after_visit_max = null;

		public $not_has_doctor_review = null;

		public $minutes_to_visit = null;
	}