<?php
    class DbScheme
    {
        protected $tables = array();

        public function addTable(TableInfo $table)
        {
            $this->tables[] = $table;
        }

		/**
		 * @return TableInfo[]
		 */
		public function getTables()
        {
            return $this->tables;
        }
    }