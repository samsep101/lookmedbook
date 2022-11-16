<?php
    class MetroTransplantationManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var MetroTransplantationManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new MetroTransplantationManager();
        }

        /**
         * @covers MetroTransplantationManager::getListByMetroStationId
         */
        function testGetListByMetroStationId()
        {
            $metro_stations = ModelManagerFactory::getByName('metro_station')->getListWithLimit(10);

            foreach ($metro_stations as $metro_station) {
                $metro_transplantations = $this->object->getListByMetroStationId($metro_station->getId());
                $this->assertTrue(is_array($metro_transplantations));

                if ($metro_transplantations)
                    foreach ($metro_transplantations as $metro_transplantation) {
                        $this->assertTrue(is_object($metro_transplantation));
                        $this->assertEquals($metro_transplantation->metro_station_id, $metro_station->getId());
                    }
            }
        }

        /**
         * @covers MetroTransplantationManager::getListByTransplantationStationId
         */
        function testGetListByTransplantationStationId()
        {
            $metro_stations = $this->object->getListWithLimit(10);

            foreach ($metro_stations as $metro_station) {
                $test_objects = $this->object->getListByTransplantationStationId($metro_station->transplantation_station_id);
                $this->assertTrue(is_array($test_objects));

                if ($test_objects)
                    foreach ($test_objects as $metro_transplantation) {
                        $this->assertTrue(is_object($metro_transplantation));
                        $this->assertEquals($metro_transplantation->transplantation_station_id, $metro_station->transplantation_station_id);
                    }
            }
        }


    }
