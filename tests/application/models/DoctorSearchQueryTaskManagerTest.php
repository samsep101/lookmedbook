<?php
    class DoctorSearchQueryTaskManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var DoctorSearchQueryTaskManager
         */
        protected $object;
        protected $hash = 'hash';
        protected $task_status_id = 1;
        protected $fail_task_status_id = null;
        protected $fail_hash = 'fail hash';


        protected function setUp()
        {
            $this->object = new DoctorSearchQueryTaskManager();
            $this->deleteAndCreateDoctorSearchQueryTask();
        }

        private function deleteAndCreateDoctorSearchQueryTask()
        {
            $this->object->deleteOneByHash($this->hash);

            $doctor_search_query_task = new DoctorSearchQueryTaskModel();
            $doctor_search_query_task->hash = $this->hash;
            $doctor_search_query_task->task_status_id = $this->task_status_id;
            $doctor_search_query_task->disableValidation();

            ModelManagerFactory::getByName('doctor_search_query_task')->save($doctor_search_query_task);
        }

        /**
         *
         * @covers DoctorSearchQueryTaskManager::getListByTaskStatusId
         */
        function testGetListByTaskStatusId()
        {

            $task_statuses = ModelManagerFactory::getByName('task_status')->getListWithLimit(10);

            foreach ($task_statuses as $task_status) {
                $doctor_search_query_tasks = $this->object->getListByTaskStatusId($task_status->getId());
                $this->assertTrue(is_array($doctor_search_query_tasks));

                if ($doctor_search_query_tasks)
                    foreach ($doctor_search_query_tasks as $doctor_search_query_task) {
                        $this->assertTrue(is_object($doctor_search_query_task));
                        $this->assertEquals($doctor_search_query_task->task_status_id, $task_status->getId());
                    }
            }
        }

        /**
         *
         * @covers DoctorSearchQueryTaskManager::getOneByHash
         */
        function testGetOneByHash()
        {

            $doctor_search_query_tasks = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($doctor_search_query_tasks as $doctor_search_query_task) {
                $test_object = $this->object->getOneByHash($doctor_search_query_task->hash);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($doctor_search_query_task->getId(), $test_object->getId());
            }
        }

        /**
         *
         * @covers DoctorSearchQueryTaskManager::getOneByHash
         */
        function testFailGetOneByHash()
        {
            $test_object = $this->object->getOneByHash($this->fail_hash);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

        /**
         * @covers DoctorSearchQueryTaskManager::getOneByTaskStatusId
         */
        function testGetOneByTaskStatusId()
        {
            $clinic_search_query_tasks = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($clinic_search_query_tasks as $clinic_search_query_task)
            {
                $test_object = $this->object->getOneByTaskStatusId($clinic_search_query_task->task_status_id);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($clinic_search_query_task->task_status_id, $test_object->task_status_id);
            }
        }

        /**
         * @covers DoctorSearchQueryTaskManager::getOneByTaskStatusId
         */
        function testFailGetOneByTaskStatusId()
        {
            $test_object = $this->object->getOneByTaskStatusId($this->fail_task_status_id);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

        /**
         * @covers DoctorSearchQueryTaskManager::setTaskStatusIdById
         */
        function testSetTaskStatusIdById()
        {
            $test_object = $this->object->getOneByTaskStatusId($this->task_status_id);
            $test_object->task_status_id = null;
            $test_object->disableValidation();
            $this->object->save($test_object);

            $this->object->setTaskStatusIdById($test_object->getId(),$this->task_status_id);

            $this->object->clearRegister();

            $clinic_search_query_task = $this->object->getOneByTaskStatusId($this->task_status_id);
            $this->assertTrue(is_object($clinic_search_query_task));
            $this->assertEquals($this->task_status_id, $clinic_search_query_task->task_status_id);
        }

        /**
         * @covers DoctorSearchQueryTaskManager::deleteOneByHash
         */
        function testDeleteOneByHash()
        {
            $this->object->deleteOneByHash($this->hash);
            $test_object = $this->object->getOneByHash($this->hash);
            $this->assertNull($test_object);
        }

        /**
         * @covers DoctorSearchQueryTaskManager::resetAllTasks
         */
        function testResetAllTasks()
        {
            $this->object->resetAllTasks();

            $clinic_search_query_tasks = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($clinic_search_query_tasks as $clinic_search_query_task)
            {
                $this->assertTrue(is_object($clinic_search_query_task));
                $this->assertEquals((int)TaskStatusModel::IN_QUEUE, $clinic_search_query_task->task_status_id);
            }
        }

    }
