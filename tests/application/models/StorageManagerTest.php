<?php
    class StorageManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var StorageManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new StorageManager();
        }

        /**
         * @covers StorageManager::getListByController
         */
        function testGetListByController()
        {
            $storages = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($storages as $storage) {
                $test_storages = $this->object->getListByController($storage->controller);
                $this->assertTrue(is_array($test_storages));

                if ($test_storages)
                    foreach ($test_storages as $test_storage) {
                        $this->assertTrue(is_object($test_storage));
                        $this->assertEquals($storage->controller, $test_storage->controller);
                    }
            }
        }

        /**
         * @covers StorageManager::getListByAction
         */
        function testGetListByAction()
        {
            $storages = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($storages as $storage) {
                $test_storages = $this->object->getListByAction($storage->action);
                $this->assertTrue(is_array($test_storages));

                if ($test_storages)
                    foreach ($test_storages as $test_storage) {
                        $this->assertTrue(is_object($test_storage));
                        $this->assertEquals($storage->action, $test_storage->action);
                    }
            }
        }

        /**
         * @covers StorageManager::getListByAccountId
         */
        function testGetListByAccountId()
        {

            $accounts = ModelManagerFactory::getByName('account')->getListWithLimit(10);

            foreach ($accounts as $account) {
                $storages = $this->object->getListByAccountId($account->getId());
                $this->assertTrue(is_array($storages));

                if ($storages)
                    foreach ($storages as $storage) {
                        $this->assertTrue(is_object($storage));
                        $this->assertEquals($storage->account_id, $account->getId());
                    }
            }
        }

        /**
         * @covers StorageManager::getListByDt
         */
        function testGetListByDt()
        {
            $storages = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($storages as $storage) {
                $test_storages = $this->object->getListByDt($storage->dt);
                $this->assertTrue(is_array($test_storages));

                if ($test_storages)
                    foreach ($test_storages as $test_storage) {
                        $this->assertTrue(is_object($test_storage));
                        $this->assertEquals($storage->dt, $test_storage->dt);
                    }
            }
        }

    }
