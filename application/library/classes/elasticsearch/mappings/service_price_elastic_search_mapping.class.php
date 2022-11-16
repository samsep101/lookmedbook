<?php

class ServicePriceElasticSearchMapping implements IElasticSearchMapping
{

    /**
     * Получить меппинг для данной сущности
     *
     * @link http://www.elasticsearch.org/guide/reference/mapping/
     * @return array
     */
    public function getFieldsMapping()
    {
        return [
            'service_id' => [
                'type' => 'integer',
            ],
            'city_id' => [
                'type' => 'integer',
            ],
            'min_price' => [
                'type' => 'integer'
            ]
        ];
    }
}