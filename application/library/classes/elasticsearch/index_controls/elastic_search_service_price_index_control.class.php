<?php

class ElasticSearchServicePriceIndexControl extends ElasticSearchModelIndexControl
{
    function __construct($indexName = null)
    {
        parent::__construct($indexName ?: Register::get('ELASTIC_SEARCH_CACHE'));
        $this->object_factory = new ServicePriceElasticSearchObjectsFactory();
    }

    /**
     * Получение типа, с которым работает данный менеджер
     *
     * @return \Elastica\Type
     */
    protected function getType()
    {
        return $this->getIndex()->getType('service_price');
    }

    public function addDocuments(array $documents)
    {
        foreach ($documents as $key => $document) {
            $docId = $document['service_id'] . '_' . $document['city_id'];
            $documents[$key] = new \Elastica\Document($docId, $document);
        }

        $this->getType()->addDocuments($documents);
        $this->getIndex()->refresh();

        return true;
    }

    /**
     * Построение объекта запроса по критериям поиска
     *
     * @param ServicePriceSearchCriteria $criteria
     * @return mixed
     */
    protected function buildQueryObject(ModelSearchCriteria $criteria)
    {
        $query = new \Elastica\Query();
        $filter = new \Elastica\Query\BoolQuery();

        if ($criteria->city_id) {
            $match = new \Elastica\Query\Term();
            $match->setTerm('city_id', $criteria->city_id);
            $filter->addFilter($match);
        }

        if ($criteria->service_ids) {
            $match = new \Elastica\Query\Terms();
            $match->setTerms('service_id', $criteria->service_ids);
            $filter->addFilter($match);
        }

        $query->setQuery($filter);

        if ($criteria->page && $criteria->by_page) {
            $size = $criteria->by_page;
            if ($criteria->get_extra_item) {
                $query->setSize($size + 1);
            } else {
                $query->setSize($size);
            }

            $from = ($criteria->page - 1) * $criteria->by_page;

            $query->setFrom($from);
        }

        return $query;
    }

    public function search(ModelSearchCriteria $criteria)
    {
        $result_query = $this->buildQueryObject($criteria);
        $data = $this->getType()->search($result_query);

        $this->total_hits = $this->getType()->count($result_query);

        $result = array();
        foreach ($data as $v) {
            /**
             * @var \Elastica\Result $v
             */
            $result[$v->getId()] = $v;
        }

        return $result;
    }
}
