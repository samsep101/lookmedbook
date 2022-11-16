<?php
    class SpecialtyManagerTest extends PHPUnit_Framework_TestCase
    {
        /**
         * @var SpecialtyManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new SpecialtyManager();
        }

        /**
         * @covers SpecialtyManager::getListByParentId
         */
        function testGetComingListByAccountId()
        {
            $specialties = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($specialties as $specialty) {
                $test_objects = $this->object->getListByParentId($specialty->parent_id);
                $this->assertTrue(is_array($test_objects));

                if ($test_objects)
                    foreach ($test_objects as $test_object) {
                        $this->assertTrue(is_object($test_object));
                        $this->assertEquals($specialty->parent_id, $test_object->parent_id);
                    }
            }
        }

        /**
         * @covers SpecialtyManager::getRootList
         */
        function testGetRootList()
        {
            $test_objects = $this->object->getRootList();
            $this->assertTrue(is_array($test_objects));

            if ($test_objects)
                foreach ($test_objects as $test_object) {
                    $this->assertTrue(is_object($test_object));
                    $this->assertNull($test_object->parent_id);
                }
        }

        /**
         * @covers SpecialtyManager::getListByDoctorId
         */
        function testGetListByDoctorId()
        {
            $doctors = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($doctors as $doctor) {
                $test_objects = $this->object->getListByDoctorId($doctor->getId());
                $this->assertTrue(is_array($test_objects));

                if ($test_objects)
                    foreach ($test_objects as $test_object) {
                        $this->assertTrue(is_object($test_object));

                        $specialty_to_doctor_manager = new SpecialtyToDoctorManager();
                        $specialty_to_doctor = $specialty_to_doctor_manager->getOneByDoctorIdAndSpecialityId($doctor->getId(), $test_object->getId());
                        $this->assertTrue(is_object($specialty_to_doctor));
                        $this->assertEquals($specialty_to_doctor->specialty_id, $test_object->getId());
                    }
            }
        }

        /**
         * @covers SpecialtyManager::getParentIdById
         */
        function testGetParentIdById()
        {
            $specialties = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($specialties as $specialty) {
                $test_object = $this->object->getParentIdById($specialty->getId());
                $this->assertEquals($specialty->parent_id, $test_object);

            }
        }

        /**
         * @covers SpecialtyManager::getParentIdById
         */
        function testFailGetParentIdById()
        {
            $test_object = $this->object->getParentIdById(null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

        /**
         * @covers SpecialtyManager::getListByClinicId
         */
        function testGetListByClinicId()
        {
            $clinics = ModelManagerFactory::getByName('clinic')->getListWithLimit(20);

            foreach ($clinics as $clinic) {
                $tests_objects = $this->object->getListByClinicId($clinic->getId());
                $this->assertTrue(is_array($tests_objects));

                if ($tests_objects)
                    foreach ($tests_objects as $test_object) {
                        $this->assertTrue(is_object($test_object));

                        $doctor_to_clinic_manager = new DoctorToClinicManager();
                        $my_specialties = $doctor_to_clinic_manager->getOneByClinicIdAndSpecialtyId($clinic->getId(), $test_object->getId());
                        $this->assertTrue(is_object($my_specialties));

                        $this->assertEquals($my_specialties->specialty_id, $test_object->getId());
                    }
            }
        }
    }
