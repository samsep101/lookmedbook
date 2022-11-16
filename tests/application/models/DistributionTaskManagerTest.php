<?php
    class DistributionTaskManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var DistributionTaskManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new DistributionTaskManager();
        }

        /**
         * @covers DistributionTaskManager::getListByDistributionId
         */
        function testGetListByDistributionId()
        {
            $distributions = ModelManagerFactory::getByName('distribution')->getListWithLimit(10);

            foreach ($distributions as $distribution) {
                $distribution_tasks = $this->object->getListByDistributionId($distribution->getId());
                $this->assertTrue(is_array($distribution_tasks));

                if ($distribution_tasks)
                    foreach ($distribution_tasks as $distribution_task) {
                        $this->assertTrue(is_object($distribution_task));
                        $this->assertEquals($distribution_task->distribution_id, $distribution->getId());
                    }
            }
        }

        /**
         * @covers DistributionTaskManager::getListByAccountId
         */
        function testGetListByAccountId()
        {
            $accounts = ModelManagerFactory::getByName('account')->getListWithLimit(10);

            foreach ($accounts as $account) {
                $distribution_tasks = $this->object->getListByAccountId($account->getId());
                $this->assertTrue(is_array($distribution_tasks));

                if ($distribution_tasks)
                    foreach ($distribution_tasks as $distribution_task) {
                        $this->assertTrue(is_object($distribution_task));
                        $this->assertEquals($distribution_task->account_id, $account->getId());
                    }
            }
        }

        /**
         * @covers DistributionTaskManager::getListByTaskStatusId
         */
        function testGetListByTaskStatusId()
        {
            $task_statuses = ModelManagerFactory::getByName('task_status')->getListWithLimit(10);

            foreach ($task_statuses as $task_status) {
                $distribution_tasks = $this->object->getListByTaskStatusId($task_status->getId());
                $this->assertTrue(is_array($distribution_tasks));

                if ($distribution_tasks)
                    foreach ($distribution_tasks as $distribution_task) {
                        $this->assertTrue(is_object($distribution_task));
                        $this->assertEquals($distribution_task->task_status_id, $task_status->getId());
                    }
            }
        }

        /**
         * @covers DistributionTaskManager::checkExistsByDistributionIdAndAccountIdAndStatusId
         */
        function testCheckExistsByDistributionIdAndAccountIdAndStatusId()
        {
            $distrubution_tasks = $this->object->getListWithLimit(20);

            if ($distrubution_tasks)
                foreach ($distrubution_tasks as $distribution_task) {
                    $this->assertTrue(is_object($distribution_task));
                    $test_objects = $this->object->checkExistsByDistributionIdAndAccountIdAndStatusId($distribution_task->distribution_id,$distribution_task->account_id,$distribution_task-task_status_id);
                    $this->assertEquals($distribution_task->getId(), $test_objects->getId());
                }
        }


    }
