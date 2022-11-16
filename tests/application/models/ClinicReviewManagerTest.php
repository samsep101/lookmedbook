<?php
    class ClinicReviewManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var ClinicReviewManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new ClinicReviewManager();
        }

        /**
         * @covers ClinicReviewManager::getConfirmedListByClinicId
         */
        function testGetConfirmedListByClinicId()
        {
            $clinic_manager = new ClinicManager();
            $clinics = $clinic_manager->getListWithLimit(10);

            $this->object->clearRegister();

            foreach ($clinics as $clinic) {
                $clinic_id = $clinic->getId();

                $reviews = $this->object->getConfirmedListByClinicId($clinic_id);

                if ($reviews)
                    foreach ($reviews as $review) {
                        $this->assertTrue(is_object($review));
                        $this->assertEquals($clinic_id, $review->clinic_id);
                        $this->assertEquals(1, $review->is_confirmed);
                    }
            }
        }


    }
