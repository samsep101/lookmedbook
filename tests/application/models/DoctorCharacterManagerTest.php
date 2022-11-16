<?php
    class DoctorCharacterManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var DoctorCharacterManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new DoctorCharacterManager();
        }

        /**
         * @covers DoctorCharacterManager::getListByIsActive
         */
        function testGetListByIsActive()
        {
            $doctor_characters = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($doctor_characters as $doctor_character) {
                $test_object = $this->object->getListByIsActive($doctor_character->is_active);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($doctor_character->getId(), $test_object->getId());
            }
        }

    }
