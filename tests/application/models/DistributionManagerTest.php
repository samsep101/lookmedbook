<?php
    class DistributionManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var DistributionManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new DistributionManager();
        }

        /**
         * @covers DistributionManager::getListByDistributionTypeId
         */
        function testGetListByDistributionTypeId()
        {

            $distribution_types = ModelManagerFactory::getByName('distribution_type')->getListWithLimit(10);

            foreach ($distribution_types as $distribution_type) {
                $distributions = $this->object->getListByDistributionTypeId($distribution_type->getId());
                $this->assertTrue(is_array($distributions));

                if ($distributions)
                    foreach ($distributions as $distribution) {
                        $this->assertTrue(is_object($distribution));
                        $this->assertEquals($distribution->distribution_type_id, $distribution_type->getId());
                    }
            }
        }

        /**
         * @covers DistributionManager::getListByTaskStatusId
         */
        function testGetListByTaskStatusId()
        {

            $task_statuses = ModelManagerFactory::getByName('task_status')->getListWithLimit(10);

            foreach ($task_statuses as $task_status) {
                $distributions = $this->object->getListByTaskStatusId($task_status->getId());
                $this->assertTrue(is_array($distributions));

                if ($distributions)
                    foreach ($distributions as $distribution) {
                        $this->assertTrue(is_object($distribution));
                        $this->assertEquals($distribution->task_status_id, $task_status->getId());
                    }
            }
        }
    }
