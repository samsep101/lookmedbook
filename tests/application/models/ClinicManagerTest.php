<?php
    class ClinicManagerTest extends PHPUnit_Framework_TestCase
    {
        /**
         * @var ClinicManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new ClinicManager();
        }

        /**
         * @covers getActiveListByDoctorId::getListByDoctorId
         */
        function testGetListByDoctorId()
        {
            $doctors = ModelManagerFactory::getByName('doctor')->getListWithLimit(20);

            foreach ($doctors as $doctor) {
                $test_objects = $this->object->getActiveListByDoctorId($doctor->getId());
                $this->assertTrue(is_array($test_objects));

                if ($test_objects)
                    foreach ($test_objects as $test_object) {
                        $this->assertTrue(is_object($test_object));

                        $doctor_to_clinic_manager = new DoctorToClinicManager();
                        $doctor_to_clinic = $doctor_to_clinic_manager->getOneByClinicIdAndDoctorId($test_object->getId(),$doctor->getId());

                        $this->assertTrue(is_object($doctor_to_clinic));
                        $this->assertEquals($doctor_to_clinic->doctor_id, $doctor->getId());
                    }
            }
        }

        /**
         * @covers ClinicManager::getFavoriteListByAccountId
         */
        function testGetFavoriteListByAccountId()
        {
            $accounts = ModelManagerFactory::getByName('account')->getListWithLimit(20);

            foreach ($accounts as $account) {
                $tests_objects = $this->object->getFavoriteListByAccountId($account->getId());
                $this->assertTrue(is_array($tests_objects));

                if ($tests_objects)
                    foreach ($tests_objects as $test_object) {
                        $this->assertTrue(is_object($test_object));

                        $my_clinic_manager = new MyClinicManager();
                        $my_clinic = $my_clinic_manager->getOneByClinicIdAndAccountId($test_object->getId(), $account->getId());

                        $this->assertTrue(is_object($my_clinic));
                        $this->assertEquals($my_clinic->clinic_id, $test_object->getId());
                    }
            }
        }

    }
