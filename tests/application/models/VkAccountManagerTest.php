<?php
    class VkAccountManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var VkAccountManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new VkAccountManager();
        }

        /**
         * @covers VkAccountManager::getOneByUid
         */
        function testGetOneByUid()
        {

            $vk_accounts = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($vk_accounts as $vk_account) {
                $test_object = $this->object->getOneByUid($vk_account->uid);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($vk_account->getId(), $test_object->getId());
            }
        }

        /**
         * @covers VkAccountManager::getOneByUid
         */
        function testFailGetOneByUid()
        {
            $test_object = $this->object->getOneByUid(null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);

        }

        /**
         * @covers VkAccountManager::getOneByAccountId
         */
        function testGetOneByAccountId()
        {
            $vk_accounts = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($vk_accounts as $vk_account) {
                $test_object = $this->object->getOneByAccountId($vk_account->account_id);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($vk_account->getId(), $test_object->getId());
            }
        }

        /**
         * @covers VkAccountManager::getOneByAccountId
         */
        function testFailGetOneByAccountId()
        {
            $test_object = $this->object->getOneByAccountId(null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

    }
