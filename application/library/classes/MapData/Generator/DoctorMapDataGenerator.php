<?php

namespace app\library\classes\MapData\Generator;

class DoctorMapDataGenerator implements MapDataGenerator
{
    private $searchParams;
    private $searchAlgorithm;

    /**
     * @param \DoctorSearchParams $searchParams
     * @param \DoctorSearchAlgorithm $searchAlgorithm
     */
    public function __construct($searchParams, $searchAlgorithm)
    {
        $this->searchAlgorithm = $searchAlgorithm;
        $this->searchParams = clone $searchParams;
        if ($this->searchParams->nearest) {
            $this->searchParams->get_extra_item = false;
        } else {
            $this->searchParams->page = 1;
            $this->searchParams->by_page = 1000;
        }
    }

    public function getSearchParams()
    {
        return $this->searchParams;
    }

    public function generate()
    {
        $this->searchAlgorithm->setIsSearchNearestAllowed(true);
        $doctors = $this->searchAlgorithm->search($this->searchParams);
        $cityId = $this->searchParams->city_id;

        $data = '';
        foreach ($doctors as $doctor) {
            foreach ($doctor->clinics as $clinic) {
                if ($cityId && $clinic->city_id !== $cityId) {
                    continue;
                }
                $data .= $doctor->getId() . '-'
                    . $clinic->getId() . ':'
                    . $clinic->address . ':'
                    . $doctor->full_name . ':'
                    . $clinic->latitude . ':'
                    . $clinic->longitude . ':4|';
            }
            unset($doctor->clinics);
        }
        return trim($data, '|');
    }
}
