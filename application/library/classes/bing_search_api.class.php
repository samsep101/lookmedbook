<?php
    class BingSearchApi implements IImageSearch
    {
        private $key_id;
        private $account_key;
        private $transactions_count;
        private $root_uri = 'https://api.datamarket.azure.com/Bing/Search/';

        /**
         * @var IHttpRequestSender
         */
        private $request_sender;

        public function __construct($key_id, $key, $transactions_count){
            $this->request_sender = new CurlRequest();
            $this->key_id = $key_id;
            $this->account_key = $key;
            $this->transactions_count = $transactions_count;
        }

        public function setRequestSender(IHttpRequestSender $request_sender)
        {
            $this->request_sender = $request_sender;
        }

        public function getRequestSender()
        {
            if(!isset($this->request_sender))
            {
                $this->request_sender = new CurlRequest();
            }

            return $this->request_sender;
        }

        public function searchImage($image_search_params)
        {
            $url = $this->buildUrl($image_search_params);
            $this->getRequestSender()->setUrl($url);

            $cred = sprintf('Authorization: Basic %s',
                base64_encode($this->account_key . ":" . $this->account_key) );
            $headers = array(
                $cred,
            );
            $this->getRequestSender()->setHeaders($headers);

            if($this->getRequestSender()->sendGetRequest())
            {
                $data = $this->getRequestSender()->getResponse();
                $data = json_decode($data, true);

                // получаем массив адресов картинок
                $result = array();
                if($data['d']['results'])
                {
                    foreach($data['d']['results'] as $item) {
                        $result[] = $item['MediaUrl'];
                    }
                }

                return $result;
            } else {
                throw new Exception('Не удалось подключиться к API.');
            }
        }

        private function buildUrl($search_params)
        {
            $request = $this->root_uri;
            $request .= 'Image?$format=json';

            if(isset($search_params['title'])) {
                $request .= '&Query=%27' .urlencode($search_params['title']) .'%27';
            }

            if(isset($search_params['size'])) {
                $request .= '&ImageFilters=%27Size%3A' .urlencode($search_params['size']) .'%27';
            }

            if(isset($search_params['count'])) {
                $request .= '&$top=' .(int)$search_params['count'];
            }

            return $request;
        }

        public function getTransactionsCount()
        {
            return $this->transactions_count;
		}

        public function incrementTransactionsCount()
        {
            $this->transactions_count++;
        }

        public function __destruct()
        {
            /**
             * @var BingKeysManager $bing_keys_manager
             */

            $bing_keys_manager = ModelManagerFactory::getByName('bing_keys');
            $bing_keys_manager->updateTransactionsCountById($this->key_id, $this->transactions_count);
        }
    }