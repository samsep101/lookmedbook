<?php

namespace app\library\classes\MapData;

class MapDataProviderFactory
{
    /**
     * @param string $datatype
     * @param \Request $request
     * @param int $cityId
     * @return MapDataProvider
     * @throws \InvalidArgumentException
     */
    public static function create($datatype, $request, $cityId)
    {
        $generator = Generator\MapDataGeneratorFactory::create($datatype, $request, $cityId);
        $file = new MapDataFile($generator->getSearchParams()->getParamsHash());
        return new MapDataProvider($generator, $file);
    }
}
