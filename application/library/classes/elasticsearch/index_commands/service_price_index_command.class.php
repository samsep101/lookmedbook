<?php

class ServicePriceIndexCommand extends ModelIndexCommand
{
    public function reIndexAll()
    {
        /** @var Db $db */
        $db = Register::get('db');
        $cityManager = new CityManager();
        $indexControl = new ElasticSearchServicePriceIndexControl();

        foreach ($cityManager->getIdList() as $cityId) {

            // get all from elastic by cityId
            $criteria = new ServicePriceSearchCriteria();
            $criteria->city_id = $cityId;
            $criteria->by_page = 100;
            $criteria->page = 0;

            $services = [];
            do {
                $criteria->page++;
                $services += $indexControl->search($criteria);
            } while ($criteria->by_page*$criteria->page < $indexControl->getTotalHits());


            // get all from db by cityId
            $q = 'SELECT s.id AS service_id, MIN(stc.price) AS min_price FROM service_category s ' .
                'INNER JOIN service_to_clinic stc ON s.id=stc.service_id ' .
                'INNER JOIN clinic c ON stc.clinic_id=c.id WHERE c.city_id = ? ' .
                'AND stc.price > 0 ' .
                'GROUP BY s.id;';

            $data = $db->query($q, [$cityId]);

            // update/create
            if ($data) {
                $docs = [];
                foreach ($data as $item) {
                    $docId = $item['service_id'] . '_' . $cityId;
                    $doc = [
                        'service_id' => $item['service_id'],
                        'min_price'  => $item['min_price'],
                        'city_id'    => $cityId,
                    ];

                    if (isset($services[$docId])) {
                        unset($services[$docId]);
                    }

                    $docs[] = $doc;
                }
                $indexControl->addDocuments($docs);
            }

            // remove
            if ($services) {
                $indexControl->deleteByIds(array_keys($services));
            }
        }
    }
}