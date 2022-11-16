<?php
    class ProductSearchCriteria extends ModelSearchCriteria
    {
        public $full_name = null;
        public $product_categories = null;
        public $is_active = null;
        public $is_leader = null;
		public $product_category_id = null;
		public $manufacturer_id = null;
		public $product_itself = null;

		public $page = 1;
		public $by_page  = 100;

        public $image_find_status_id;
		public $fill_information_status_id = null;
    }