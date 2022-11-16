<?php
    class VisitManagerTest extends PHPUnit_Framework_TestCase
    {
        /**
         * @var VisitManager
         */
        protected $object;
        protected $fail_schedule_id = NULL;

        protected function setUp()
        {
            $this->object = new VisitManager();
        }

        /**
         * @covers VisitManager::checkFirstVisitByDoctorIdAndAccountId
         */
        function testCheckFirstVisitByDoctorIdAndAccountId()
        {
            $visits = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($visits as $visit)
            {
                $test_object = $this->object->checkFirstVisitByDoctorIdAndAccountId($visit->doctor_id, $visit->account);

                if ($test_object)
                    $this->assertTrue($test_object);
            }
        }


        /**
         * @covers VisitManager::getPastListByAccountId
         */
        function testGetPastListByAccountId()
        {
            $accounts = ModelManagerFactory::getByName('account')->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($accounts as $account) {
                $test_objects = $this->object->getPastListByAccountId($account->getId());
                $this->assertTrue(is_array($test_objects));

                if ($test_objects)
                    foreach ($test_objects as $test_object) {
                        $test_visit = $this->object->getOneById($test_object->getId());
                        $this->assertTrue(is_object($test_visit));
                        $this->assertEquals($test_object->getId(), $test_visit->getId());
                    }
            }
        }

        /**
         * @covers VisitManager::getComingListByAccountId
         */
        function testGetComingListByAccountId()
        {
            $accounts = ModelManagerFactory::getByName('account')->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($accounts as $account) {
                $test_objects = $this->object->getComingListByAccountId($account->getId());
                $this->assertTrue(is_array($test_objects));

                if ($test_objects)
                    foreach ($test_objects as $test_object) {
                        $test_visit = $this->object->getOneById($test_object->getId());
                        $this->assertTrue(is_object($test_visit));
                        $this->assertEquals($test_object->getId(), $test_visit->getId());
                    }
            }
        }

        /**
         * @covers VisitManager::getOneByScheduleId
         */
        function testGetOneByScheduleId()
        {
            $visits = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($visits as $visit) {
                $test_object = $this->object->getOneByScheduleId($visit->schedule_id);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($visit->getId(), $test_object->getId());
            }
        }

        /**
         * @covers VisitManager::getOneByScheduleId
         */
        function testFailGetOneByScheduleId()
        {
            $test_object = $this->object->getOneByScheduleId(null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

        /**
         * @covers VisitManager::getListByNotificationDt
         */
        function testGetListByNotificationDt()
        {
            $visits_managers = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($visits_managers as $visit_manager) {
                $test_objects = $this->object->getListByNotificationDt($visit_manager->notification_dt);
                $this->assertTrue(is_array($test_objects));

                if ($test_objects)
                    foreach ($test_objects as $test_object) {
                        $test_visit_manager = $this->object->getOneById($test_object->getId());
                        $this->assertTrue(is_object($test_visit_manager));
                        $this->assertEquals($test_object->getId(), $test_visit_manager->getId());
                    }
            }
        }

		public function testSearchCriteriaVisitStatusId()
		{
			/**
			 * @var VisitStatusManager $visit_status_manager
			 */
			$visit_status_manager = ModelManagerFactory::getByName('visit_status');

			/**
			 * @var VisitStatusModel[] $statuses
			 */
			$statuses = $visit_status_manager->getList();

			foreach($statuses as $status)
			{
				$visit_criteria = new VisitSearchCriteria();
				$visit_criteria->visit_status_id = $status->getId();

				$visits = $this->object->getListByModelSearchCriteria($visit_criteria);

				$this->assertTrue(is_array($visits));

				if ($visits)
					foreach($visits as $visit)
					{
						$this->assertEquals($status->getId(), $visit->status_id);
					}
			}
		}


    }
