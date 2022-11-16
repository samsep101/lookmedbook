<?php
    class FbAccountManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var FbAccountManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new FbAccountManager();
        }

        /**
         * @covers FbAccountManager::getOneByUid
         */
        function testGetOneByUid()
        {

            $fb_accounts = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($fb_accounts as $fb_account) {
                $test_object = $this->object->getOneByUid($fb_account->uid);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($fb_account->getId(), $test_object->getId());
            }
        }

        /**
         * @covers FbAccountManager::getOneByUid
         */
        function testFailGetOneByUid()
        {
            $test_object = $this->object->getOneByUid(null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);

        }

        /**
         * @covers FbAccountManager::getOneByAccountId
         */
        function testGetOneByAccountId()
        {

            $fb_accounts = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($fb_accounts as $fb_account) {
                $test_object = $this->object->getOneByAccountId($fb_account->account_id);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($fb_account->getId(), $test_object->getId());
            }
        }

        /**
         * @covers FbAccountManager::getOneByAccountId
         */
        function testFailGetOneByAccountId()
        {
            $test_object = $this->object->getOneByAccountId(null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

    }
