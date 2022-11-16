<?php
    class DoctorEducationManagerTest extends PHPUnit_Framework_TestCase
    {
        /**
         * @var DoctorEducationManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new DoctorEducationManager();
        }

        /**
         * @covers DoctorEducationManager::getOneByDoctorIdAndUniversityId
         */
        function testGetOneByDoctorIdAndUniversityId()
        {

            $doctors_universities = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($doctors_universities as $doctor_university) {
                $test_object = $this->object->getOneByDoctorIdAndUniversityId($doctor_university->doctor_id, $doctor_university->university_id);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($doctor_university->doctor_id, $test_object->doctor_id);
                $this->assertEquals($doctor_university->university_id, $test_object->university_id);
            }
        }

        /**
         * @covers DoctorEducationManager::getOneByDoctorIdAndUniversityId
         */
        function testFailGetOneByDoctorIdAndUniversityId()
        {
            $test_object = $this->object->getOneByDoctorIdAndUniversityId(null,null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }
    }
