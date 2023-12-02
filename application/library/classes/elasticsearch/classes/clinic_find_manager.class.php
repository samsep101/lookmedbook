<?php
    class ClinicFindManager
    {
        /**
         * @var \Elastica\Client
         */
        private $elastica;

        protected function getElasticaApi()
        {
            if(!$this->elastica)
            {
                $this->elastica = ElasticaFactory::getApi();
            }

            return $this->elastica;
        }

        /**
         * @param $name
         * @param $address
         * @return ClinicModel
         */
        public function search($name, $address)
        {
            $api = $this->getElasticaApi();
            $index = $api->getIndex(Register::get('ELASTIC_SEARCH_INDEX'));
            $type = $index->getType('clinic');

            $query  = str_replace('/', '\\', $name.' '.$address);

            $search_data = $type->search($query);

            /**
             * @var ClinicManager $clinic_manager
             */
            $clinic_manager = ModelManagerFactory::getByName('clinic');

            $result = array();

            foreach($search_data as $search_item)
            {
                /**
                 * @var \Elastica\Result $search_item
                 */
                if($search_item->getScore() >= 2)
                {
                    $clinic = $clinic_manager->getOneById($search_item->getId());
                    if($clinic->city_id == 2)
                    {
                        $result[] = array(
                            'clinic' => $clinic_manager->getOneById($search_item->getId()),
                            'score' => $search_item->getScore()
                        );
                    }
                }
            }

            return $result;
        }
    }