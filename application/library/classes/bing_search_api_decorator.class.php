<?php
    class BingSearchApiDecorator
    {
        /**
         * @var BingSearchApi $api
         */

        private $api;

        public function __construct(IImageSearch $api)
        {
            $this->api = $api;
        }

        public function incrementTransactionsCount()
        {
            return $this->api->getTransactionsCount() + 1;
        }
    }