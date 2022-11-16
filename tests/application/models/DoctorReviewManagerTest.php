<?php
    class DoctorReviewManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var DoctorReviewManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new DoctorReviewManager();
        }

        /**
         * @covers DoctorReviewManager::getConfirmedListByDoctorId
         */
        function testGetConfirmedListByDoctorId()
        {
            $doctors = ModelManagerFactory::getByName('doctor')->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($doctors as $doctor) {
                $reviews = $this->object->getConfirmedListByDoctorId($doctor->getId());
                $this->assertTrue(is_array($reviews));

                if ($reviews)
                    foreach ($reviews as $review) {
                        $this->assertTrue(is_object($review));
                        $this->assertEquals($doctor->getId(), $review->clinic_id);
                        $this->assertEquals(1, $review->is_confirmed);
                    }
            }
        }

        /**
         * @covers DoctorReviewManager::getCountConfirmedListByDoctorId
         */
        function testGetCountConfirmedListByDoctorId()
        {
            $doctors = ModelManagerFactory::getByName('doctor')->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($doctors as $doctor) {
                $doctor_id = $doctor->getId();

                $reviews = $this->object->getCountConfirmedListByDoctorId($doctor_id);

                if ($reviews)
                    $this->assertTrue($reviews);

            }
        }


    }
