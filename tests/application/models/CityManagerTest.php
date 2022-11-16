<?php
    class CityManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var CityManager
         */
        protected $object;
        protected $fail_city_name = 'fail';

        protected function setUp()
        {
            $this->object = new CityManager();
        }

        /**
         * @covers CityManager::getIdByName
         */
        function testGetIdByName()
        {
            $cities = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($cities as $city) {
                $test_id = $this->object->getIdByName($city->name);
                $this->assertEquals($city->getId(), $test_id);
            }
        }

        /**
         * @covers CityManager::getIdByName
         */
        function testFailGetIdByName()
        {
            $cities = $this->object->getIdByName($this->fail_city_name);
            $this->assertFalse(is_object($cities));
            $this->assertNull($cities);
        }

        /**
         * @covers CityManager::getOneByName
         */
        function testGetOneByName()
        {

            $cities = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($cities as $city) {
                $test_object = $this->object->getOneByName($city->name);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($city->getId(), $test_object->getId());
            }
        }

        /**
         * @covers CityManager::getOneByName
         */
        function testFailGetOneByName()
        {
            $cities = $this->object->getOneByName($this->fail_city_name);
            $this->assertFalse(is_object($cities));
            $this->assertNull($cities);
        }

    }
