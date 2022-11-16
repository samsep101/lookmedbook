<?php
    class MessageManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var MessageManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new MessageManager();
        }

        /**
         * @covers MessageManager::getListByToAccountId
         */
        function testGetListByToAccountId()
        {
            $accounts = ModelManagerFactory::getByName('account')->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($accounts as $account)
            {
                $test_objects = $this->object->getListByToAccountId($account->getId());
                $this->assertTrue(is_array($test_objects));

                if ($test_objects)
                    foreach ($test_objects as $test_object)
                    {
                        $this->assertTrue(is_object($test_object));
                        $this->assertEquals($test_object->to_account_id, $account->getId());
                    }
            }
        }

        /**
         * @covers MessageManager::getListByDt
         */
        function testGetListByDt()
        {
            $messages = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($messages as $message)
            {
                $test_objects = $this->object->getListByToAccountId($message->dt);
                $this->assertTrue(is_array($test_objects));

                if ($test_objects)
                    foreach ($test_objects as $test_object)
                    {
                        $this->assertTrue(is_object($test_object));
                        $this->assertEquals($test_object->dt, $message->dt);
                    }
            }
        }

        /**
         * @covers MessageManager::getListByIsReaded
         */
        function testGetListByIsReaded()
        {
            $messages = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($messages as $message)
            {
                $test_objects = $this->object->getListByIsReaded($message->is_readed);
                $this->assertTrue(is_array($test_objects));

                if ($test_objects)
                    foreach ($test_objects as $test_object)
                    {
                        $this->assertTrue(is_object($test_object));
                        $this->assertEquals($test_object->is_readed, $message->is_readed);
                    }
            }
        }

        /**
         * @covers MessageManager::getCountUnreadedByAccountId
         */
        function testGetCountUnreadedByAccountId()
        {
            $accounts = ModelManagerFactory::getByName('account')->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($accounts as $account)
            {
                $test_object = $this->object->getCountUnreadedByAccountId($account->getId());

                if ($test_object)
                    $this->assertTrue($test_object);

            }
        }

        /**
         * @covers MessageManager::readMessage
         */
        function testReadMessage()
        {
            $messages = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($messages as $message)
            {
                $test_message = $this->object->getOneById($message->getId());
                $test_message->is_readed = null;
                $test_message->disableValidation();
                $this->object->save($test_message);

                $this->object->readMessage($test_message->to_account_id, $test_message->getId());

                $this->object->clearRegister();

                $test_object = $this->object->getOneById($test_message->getId());
                $this->assertTrue(is_object($test_object));
                $this->assertEquals(1, $test_object->is_readed);
            }
        }


    }
