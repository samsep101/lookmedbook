<?php

namespace app\library\classes\MapData\Generator;

class AnalysisMapDataGenerator implements MapDataGenerator
{
    private $searchParams;
    private $labManager;

    /**
     * @param \LaboratorySearchParams $searchParams
     * @param \LaboratoryManager $labManager
     */
    public function __construct($searchParams, $labManager)
    {
        $this->labManager = $labManager;
        $this->searchParams = clone $searchParams;
    }

    public function getSearchParams()
    {
        return $this->searchParams;
    }

    public function generate()
    {
        $labs = $this->labManager->getListByModelSearchCriteria($this->searchParams);

        $data = '';
        foreach ($labs as $lab) {
            $data .= $lab->getId() . ':'
                . $lab->address . ':'
                . $lab->name . ':'
                . $lab->latitude . ':'
                . $lab->longitude . ':4|';
        }

        return trim($data, '|');
    }
}
