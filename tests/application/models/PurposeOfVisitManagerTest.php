<?php
    class PurposeOfVisitManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var PurposeOfVisitManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new PurposeOfVisitManager();
        }

        /**
         * @covers PurposeOfVisitManager::getListBySpecialtyId
         */
        function testGetListBySpecialtyId()
        {
            $specialties = ModelManagerFactory::getByName('specialty')->getListWithLimit(10);

            foreach ($specialties as $specialty) {
                $purpose_of_visits = $this->object->getListBySpecialtyId($specialty->getId());
                $this->assertTrue(is_array($purpose_of_visits));

                if ($purpose_of_visits)
                    foreach ($purpose_of_visits as $purpose_of_visit) {
                        $this->assertTrue(is_object($purpose_of_visit));

                        $purpose_of_visit_to_specialty_manager = new PurposeOfVisitToSpecialtyManager();
                        $my_purposes = $purpose_of_visit_to_specialty_manager->getOneByPurposeOfVisitIdAndSpecialtyId($purpose_of_visit->getId(), $specialty->getId());
                        $this->assertTrue(is_object($my_purposes));

                        $this->assertEquals($my_purposes->purpose_of_visit_id, $purpose_of_visit->getId());
                    }
            }
        }

        /**
         * @covers PurposeOfVisitManager::getListByDoctorId
         */
        function testGetListByDoctorId()
        {
            $doctors = ModelManagerFactory::getByName('specialty')->getListWithLimit(10);

            foreach ($doctors as $doctor) {
                $purpose_of_visits = $this->object->getListByDoctorId($doctor->getId());
                $this->assertTrue(is_array($purpose_of_visits));

                if ($purpose_of_visits)
                    foreach ($purpose_of_visits as $purpose_of_visit) {
                        $this->assertTrue(is_object($purpose_of_visit));

                        $purpose_of_visit_to_doctor_manager = new PurposeOfVisitToDoctorManager();
                        $my_purposes = $purpose_of_visit_to_doctor_manager->getOneByPurposeOfVisitIdAndDoctorId($purpose_of_visit->getId(), $doctor->getId());
                        $this->assertTrue(is_object($my_purposes));

                        $this->assertEquals($my_purposes->purpose_of_visit_id, $purpose_of_visit->getId());
                    }
            }
        }

    }
