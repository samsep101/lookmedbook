<?php
    class VisitRatingManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var VisitRatingManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new VisitRatingManager();
        }

        /**
         * @covers VisitRatingManager::getOneByVisitIdAndAccountId
         */
        function testGetOneByVisitIdAndAccountId()
        {
            $visits = ModelManagerFactory::getByName('visit')->getListWithLimit(10);
            $accounts = ModelManagerFactory::getByName('account')->getListWithLimit(10);

            foreach ($visits as $visit) {
                foreach ($accounts as $account) {

                    $visit_ratings = $this->object->getListWithLimit(20);

                    $this->object->clearRegister();

                    foreach ($visit_ratings as $visit_rating) {
                        $test_object = $this->object->getOneByVisitIdAndAccountId($visit->getId(), $account->getId());
                        $this->assertTrue(is_object($visit_rating));
                        $this->assertEquals($visit_rating->doctor_id, $test_object->getId());
                    }

                }
            }
        }

        /**
         * @covers VisitRatingManager::getOneByVisitIdAndAccountId
         */
        function testFailGetOneByVisitIdAndAccountId()
        {
            $test_object = $this->object->getOneByVisitIdAndAccountId(null,null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

        /**
         * @covers VisitRatingManager::getListByDoctorId
         */
        function testGetListByDoctorId()
        {
            $doctors = ModelManagerFactory::getByName('doctor')->getListWithLimit(10);

            foreach ($doctors as $doctor) {
                $visit_ratings = $this->object->getListByDoctorId($doctor->getId());
                $this->assertTrue(is_array($visit_ratings));

                if ($visit_ratings)
                    foreach ($visit_ratings as $visit_rating) {
                        $this->assertTrue(is_object($visit_rating));
                        $this->assertEquals($visit_rating->doctor_id, $doctor->getId());
                    }
            }
        }

        /**
         * @covers VisitRatingManager::getListByClinicId
         */
        function testGetListByClinicId()
        {
            $clinics = ModelManagerFactory::getByName('clinic')->getListWithLimit(10);

            foreach ($clinics as $clinic) {
                $visit_ratings = $this->object->getListByClinicId($clinic->getId());
                $this->assertTrue(is_array($visit_ratings));

                if ($visit_ratings)
                    foreach ($visit_ratings as $visit_rating) {
                        $this->assertTrue(is_object($visit_rating));
                        $this->assertEquals($visit_rating->clinic_id, $clinic->getId());
                    }
            }
        }

    }
