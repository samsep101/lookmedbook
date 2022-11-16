<?php
    class ModelIterator implements Iterator
    {
        private $position = 0;
        private $registry = array();

        private $page = 0;
        private $by_page = 100;

        /**
         * @var ModelManager
         */
        private $manager;

        private $search_params = null;

        public function __construct()
        {
            $this->search_params = new SearchParams();
        }

        public function setManager(ModelManager $manager)
        {
            $this->manager = $manager;
        }

        private function readRange()
        {
            $this->registry = $this->manager->getListWithPaging($this->page, $this->by_page);
            $this->manager->clearRegister();
        }


        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Return the current element
         * @link http://php.net/manual/en/iterator.current.php
         * @return mixed Can return any type.
         */
        public function current()
        {
            $registry_index = $this->getRegistryIndex();
            return $this->registry[$registry_index];
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Move forward to next element
         * @link http://php.net/manual/en/iterator.next.php
         * @return void Any returned value is ignored.
         */
        public function next()
        {
            $this->position++;
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Return the key of the current element
         * @link http://php.net/manual/en/iterator.key.php
         * @return mixed scalar on success, or null on failure.
         */
        public function key()
        {
            return $this->position;
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Checks if current position is valid
         * @link http://php.net/manual/en/iterator.valid.php
         * @return boolean The return value will be casted to boolean and then evaluated.
         * Returns true on success or false on failure.
         */
        public function valid()
        {
            if ($this->position > ($this->page * $this->by_page))
            {
                $this->page++;
                $this->readRange();
            }

            $registry_index = $this->getRegistryIndex();

            return (isset($this->registry[$registry_index])) && $this->registry[$registry_index];
        }

        private function getRegistryIndex()
        {
            return ($this->position - 1) % $this->by_page;
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Rewind the Iterator to the first element
         * @link http://php.net/manual/en/iterator.rewind.php
         * @return void Any returned value is ignored.
         */
        public function rewind()
        {
            $this->position = 1;
            $this->page = 0;
        }

    }