<?php
    class CountryManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var CountryManager
         */
        protected $object;
        protected $fail_name = 'fail';

        protected function setUp()
        {
            $this->object = new CountryManager();
        }

        /**
         *
         * @covers CountryManager::getIdByName
         */
        function testGetIdByName()
        {
            $countries = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            if ($countries)
                foreach ($countries as $country) {
                    $this->assertTrue(is_object($country));
                    $test_id = $this->object->getIdByName($country->name);
                    $this->assertEquals($country->getId(), $test_id);
                }
        }

        /**
         *
         * @covers CountryManager::getIdByName
         */
        function testFailGetIdByName()
        {
            $test_object = $this->object->getIdByName($this->fail_name);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }


    }
