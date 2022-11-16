<?php
    class OkAccountManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var OkAccountManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new OkAccountManager();
        }

        /**
         * @covers OkAccountManager::getOneByUid
         */
        function testGetOneByUid()
        {
            $ok_accounts = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($ok_accounts as $ok_account) {
                $test_object = $this->object->getOneByUid($ok_account->uid);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($ok_account->getId(), $test_object->getId());
            }
        }

        /**
         * @covers OkAccountManager::getOneByUid
         */
        function testFailGetOneByUid()
        {
            $test_object = $this->object->getOneByUid(null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);

        }

        /**
         * @covers OkAccountManager::getOneByAccountId
         */
        function testGetOneByAccountId()
        {
            $ok_accounts = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($ok_accounts as $ok_account) {
                $test_object = $this->object->getOneByAccountId($ok_account->account_id);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($ok_account->getId(), $test_object->getId());
            }
        }

        /**
         * @covers OkAccountManager::getOneByAccountId
         */
        function testFailGetOneByAccountId()
        {
            $test_object = $this->object->getOneByAccountId(null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }


    }
