<?php
    class DoctorManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var DoctorManager
         */
        protected $object;
        protected $first_name = 'Test Doctor';
        protected $is_leave_the_house = 1;
        protected $rate = 3;
        protected $advice_rate = 3;
        protected $test_doctor_id;

        protected function setUp()
        {
            $this->object = new DoctorManager();
            $this->test_doctor_id = $this->deleteAndCreateDoctor();
        }

        private function deleteAndCreateDoctor()
        {
            $this->object->deleteByFirstName($this->first_name);

            $doctor = new DoctorModel();
            $doctor->first_name = $this->first_name;
            $doctor->is_leave_the_house = $this->is_leave_the_house;
            $doctor->rate = $this->rate;
            $doctor->advice_rate = $this->advice_rate;
            $doctor->disableValidation();

            ModelManagerFactory::getByName('doctor')->save($doctor);
            return $doctor->getId();
        }

        /**
         * @covers DoctorManager::getOneByFirstName
         */
        function testGetOneByFirstName()
        {
            $doctor = $this->object->getOneByFirstName($this->first_name);
            $this->assertTrue(is_object($doctor));
            $this->assertEquals($this->first_name, $doctor->first_name);
        }

        /**
         * @covers DoctorManager::getOneByFirstName
         */
        function testFailGetOneByFirstName()
        {
            $doctor = $this->object->getOneByFirstName('');
            $this->assertFalse(is_object($doctor));
            $this->assertNull($doctor);
        }

        /**
         * @covers DoctorManager::getActiveListByClinicId
         */
        function testGetActiveListByClinicId()
        {
            $clinics = ModelManagerFactory::getByName('clinic')->getListWithLimit(10);

            foreach ($clinics as $clinic) {
                $test_objects = $this->object->getActiveListByClinicId($clinic->getId());
                $this->assertTrue(is_array($test_objects));

                if ($test_objects)

                    foreach ($test_objects as $test_object) {
                        $this->assertTrue((bool)$test_object->is_active);
                        $this->assertTrue(is_object($test_object));

                        $doctor_to_clinic_manager = new DoctorToClinicManager();
                        $doctor_to_clinic = $doctor_to_clinic_manager->getOneByClinicIdAndDoctorId($clinic->getId(), $test_object->getId());

                        $this->assertTrue(is_object($doctor_to_clinic));
                        $this->assertEquals($doctor_to_clinic->clinic_id, $clinic->getId());

                    }
            }
        }

        /**
         * @covers DoctorManager::getActiveList
         */
        function testGetActiveList()
        {
            $doctors = $this->object->getActiveList();

            $this->object->clearRegister();

            if ($doctors)
                foreach ($doctors as $doctor)
                    $this->assertTrue(is_object($doctor));
            $this->assertEquals(1, $doctor->is_active);
        }


        /**
         * @covers DoctorManager::setRateAndAdviceRateById
         */
        function testSetRateById()
        {
            $doctor_object = $this->object->getOneById($this->test_doctor_id);
            $doctor_object->rate = null;
            $doctor_object->advice_rate = null;
            $doctor_object->disableValidation();
            $this->object->save($doctor_object);

            $this->object->setRateAndAdviceRateById($doctor_object->getId(), $this->rate, $this->advise_rate);

            $this->object->clearRegister();

            $test_doctor = $this->object->getOneById($doctor_object->getId());
            $this->assertTrue(is_object($test_doctor));
            $this->assertEquals($this->rate, $test_doctor->rate);
            $this->assertEquals($this->advise_rate, $test_doctor->advice_rate);

        }

        /**
         * @covers DoctorManager::getFavoriteListByAccountId
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
                        $my_doctor = ModelManagerFactory::getByName('my_doctor')->getOneByDoctorIdAndAccountId($test_object->getId(), $account->getId());
                        $this->assertTrue(is_object($my_doctor));
                        $this->assertEquals($my_doctor->doctor_id, $test_object->getId());
                    }
            }
        }

        /**
         * @covers DoctorManager::deleteByFirstName
         */
        function testDeleteByFirstName()
        {
            $this->object->deleteByFirstName($this->first_name);
            $doctor = $this->object->getOneByFirstName($this->first_name);
            $this->assertNull($doctor);
        }

    }
