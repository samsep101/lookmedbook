<?php
    class DoctorToClinicManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var DoctorToClinicManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new DoctorToClinicManager();
        }

        /**
         * @covers DoctorToClinicManager::getListByDoctorId
         */
        function testGetListByDoctorId()
        {

            $doctors = ModelManagerFactory::getByName('doctor')->getListWithLimit(10);

            foreach ($doctors as $doctor) {
                $doctor_to_clinics = $this->object->getListByDoctorId($doctor->getId());
                $this->assertTrue(is_array($doctor_to_clinics));

                if ($doctor_to_clinics)
                    foreach ($doctor_to_clinics as $doctor_to_clinic) {
                        $this->assertTrue(is_object($doctor_to_clinic));
                        $this->assertEquals($doctor_to_clinic->doctor_id, $doctor->getId());
                    }
            }
        }

        /**
         * @covers DoctorToClinicManager::getListByClinicId
         */
        function testGetListByClinicId()
        {

            $clinics = ModelManagerFactory::getByName('clinic')->getListWithLimit(10);

            foreach ($clinics as $clinic) {
                $doctor_to_clinics = $this->object->getListByClinicId($clinic->getId());
                $this->assertTrue(is_array($doctor_to_clinics));

                if ($doctor_to_clinics)
                    foreach ($doctor_to_clinics as $doctor_to_clinic) {
                        $this->assertTrue(is_object($doctor_to_clinic));
                        $this->assertEquals($doctor_to_clinic->clinic_id, $clinic->getId());
                    }
            }
        }

        /**
         *
         * @covers DoctorToClinicManager::getFirstVisitPriceByDoctorIdAndClinicId
         */
        function testGetFirstVisitPriceByDoctorIdAndClinicId()
        {
            $doctor_to_clinics = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            if ($doctor_to_clinics)
                foreach ($doctor_to_clinics as $doctor_to_clinic) {
                    $this->assertTrue(is_object($doctor_to_clinic));
                    $test_price = $this->object->getFirstVisitPriceByDoctorIdAndClinicId($doctor_to_clinic->doctor_id, $doctor_to_clinic->clinic_id);
                    $this->assertEquals($doctor_to_clinic->first_visit_price, $test_price);
                }
        }


        /**
         *
         * @covers DoctorToClinicManager::getSecondVisitPriceByDoctorIdAndClinicId
         */
        function testGetSecondVisitPriceByDoctorIdAndClinicId()
        {
            $doctor_to_clinics = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            if ($doctor_to_clinics)
                foreach ($doctor_to_clinics as $doctor_to_clinic) {
                    $this->assertTrue(is_object($doctor_to_clinic));
                    $test_price = $this->object->getSecondVisitPriceByDoctorIdAndClinicId($doctor_to_clinic->doctor_id, $doctor_to_clinic->clinic_id);
                    $this->assertEquals($doctor_to_clinic->second_visit_price, $test_price);
                }
        }


        /**
         *
         * @covers DoctorToClinicManager::getSpecialtyIdByDoctorIdAndClinicId
         */
        function testGetSpecialtyIdByDoctorIdAndClinicId()
        {
            $doctor_to_clinics = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            if ($doctor_to_clinics)
                foreach ($doctor_to_clinics as $doctor_to_clinic) {
                    $this->assertTrue(is_object($doctor_to_clinic));
                    $test_object = $this->object->getSpecialtyIdByDoctorIdAndClinicId($doctor_to_clinic->doctor_id, $doctor_to_clinic->clinic_id);
                    $this->assertEquals($doctor_to_clinic->specialty_id, $test_object);
                }
        }
    }
