<?php
    class SettingsManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var SettingsManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new SettingsManager();
        }

        /**
         * @covers SettingsManager::getOneByCode
         */
        function testGetOneByCode()
        {
            $settingses = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($settingses as $settings) {
                $test_object = $this->object->getOneByCode($settings->code);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($settings->getId(), $test_object->getId());
            }
        }

        /**
         * @covers SettingsManager::getOneByCode
         */
        function testFailGetOneByCode()
        {
            $test_object = $this->object->getOneByCode('');
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

        /**
         * @covers SettingsManager::get
         */
        function testGet()
        {
            $settingses = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($settingses as $settings) {
                $test_object = $this->object->get($settings->code);
                $this->assertEquals($settings->value, $test_object);
            }
        }

        /**
         * @covers SettingsManager::get
         */
        function testFailGet()
        {
            $test_object = $this->object->get('');
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }
    }
