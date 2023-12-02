<?php

namespace app\library\classes\MapData\Generator;

interface MapDataGenerator
{
    /**
     * Generates encoded map data.
     * @return string
     */
    public function generate();

    /**
     * Returns the criteria used to generate the data.
     * @return \ModelSearchCriteria
     */
    public function getSearchParams();
}
