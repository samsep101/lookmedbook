<?php

namespace app\library\classes\MapData\Generator;

class ClinicMapDataGenerator implements MapDataGenerator
{
    private $searchParams;
    private $searchAlgorithm;
    private $clinicManager;

    /**
     * @param \ClinicSearchParams $searchParams
     * @param \ClinicSearchAlgorithm $searchAlgorithm
     * @param \ClinicManager $clinicManager
     */
    public function __construct($searchParams, $searchAlgorithm, $clinicManager)
    {
        $this->searchAlgorithm = $searchAlgorithm;
        $this->clinicManager = $clinicManager;
        $this->searchParams = clone $searchParams;
        if ($this->searchParams->nearest) {
            $this->searchParams->get_extra_item = false;
        } else {
            $this->searchParams->page = null;
            $this->searchParams->by_page = null;
        }
    }

    public function getSearchParams()
    {
        return $this->searchParams;
    }

    public function generate()
    {
        if ($this->searchParams->primary_clinic_id) {
            $clinics = $this->clinicManager->getChildsClinic($this->searchParams->primary_clinic_id);
        } else {
            $this->searchAlgorithm->setIsSearchNearestAllowed(true);
            $clinics = $this->searchAlgorithm->search($this->searchParams);
        }

        $s = '';
        foreach ($clinics as $clinic) {
            $s .= $clinic->getId() . ':'
                . $clinic->address . ':'
                . str_replace('|', ' ', $clinic->name) . ':'
                . $clinic->latitude . ':'
                . $clinic->longitude . ':4|';
        }
        return trim($s, '|');
    }
}
