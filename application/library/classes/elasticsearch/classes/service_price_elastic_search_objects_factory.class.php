<?php
class ServicePriceElasticSearchObjectsFactory
{
    /**
     * @return IElasticSearchMapping
     */
    public function getMapper()
    {
        return new ServicePriceElasticSearchMapping();
    }

    /**
     * @return IElasticSearchFormatter
     */
    public function getFormatter()
    {
        return new ServicePriceElasticSearchFormatter();
    }
}