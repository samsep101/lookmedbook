<?php
    class AccountPhoneManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var AccountPhoneManager
         */
        protected $object;

        protected $fail_account_id = null;
        protected $fail_phone = '';

        protected $phone = '+375291111111';
        protected $code = 'test';
        protected $date = '2012-12-21 23:59:59';

        protected function setUp()
        {
            $this->object = new AccountPhoneManager();
            $this->deleteAndCreateAccountPhone();
        }

        private function deleteAndCreateAccountPhone()
        {
            $this->object->deleteByPhone($this->phone);

            $account_manager = new AccountManager();
            $accounts = $account_manager->getListWithLimit(1);

            $this->object->clearRegister();

            foreach ($accounts as $account) {
                $account_phone = new AccountPhoneModel();
                $account_phone->phone = $this->phone;
                $account_phone->account_id = $account->getId();
                $account_phone->code = $this->code;
                $account_phone->is_confirmed = 1;
                $account_phone->disableValidation();

                ModelManagerFactory::getByName('account_phone')->save($account_phone);
            }

        }

        /**
         * @covers AccountPhoneManager::getOneByAccountIdAndPhone
         */
        function testGetOneByAccountIdAndPhone()
        {
            $phones = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($phones as $phone) {
                $test_object = $this->object->getOneByAccountIdAndPhone($phone->account_id, $phone->phone);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($phone->getId(), $test_object->getId());
            }
        }

        /**
         * @covers AccountPhoneManager::getOneByAccountIdAndPhone
         */
        function testFailGetOneByAccountIdAndPhone()
        {
            $phone = $this->object->getOneByAccountIdAndPhone($this->fail_account_id, $this->fail_phone);
            $this->assertFalse(is_object($phone));
            $this->assertNull($phone);
        }

        /**
         * @covers AccountPhoneManager::getConfirmedListByAccountId
         */
        function testGetConfirmedListByAccountId()
        {
            $account_manager = new AccountManager();
            $accounts = $account_manager->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($accounts as $account) {
                $account_id = $account->getId();

                $phones = $this->object->getConfirmedListByAccountId($account_id);

                if ($phones)
                    foreach ($phones as $phone) {
                        $this->assertEquals($account_id, $phone->account_id);
                        $this->assertEquals(1, $phone->is_confirmed);
                    }
            }
        }

        /**
         * @covers AccountPhoneManager::getNotConfirmedListByAccountId
         */
        function testGetNotConfirmedListByAccountId()
        {
            $account_manager = new AccountManager();
            $accounts = $account_manager->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($accounts as $account) {
                $account_id = $account->getId();

                $phones = $this->object->getNotConfirmedListByAccountId($account_id);

                if ($phones)
                    foreach ($phones as $phone) {
                        $this->assertEquals($account_id, $phone->account_id);
                        $this->assertEquals(1, $phone->is_confirmed);
                    }
            }
        }

        /**
         * @covers AccountPhoneManager::setConfirmByAccountIdAndPhone
         */
        function testSetConfirmByAccountIdAndPhone()
        {
            $phone = $this->object->getOneByPhone($this->phone);
            $phone->is_confirmed = 0;
            $phone->disableValidation();
            $this->object->save($phone);

            $this->object->setConfirmByAccountIdAndPhone( $phone->account_id, $phone->phone);

            $this->object->clearRegister();

            $test_phone = $this->object->getOneByPhone($this->phone);
            $this->assertTrue(is_object($test_phone));
            $this->assertEquals(1, $test_phone->is_confirmed);
        }

        /**
         * @covers AccountPhoneManager::setCodeAndDtById
         */
        function testSetCodeAndDtById()
        {
            $phone = $this->object->getOneByPhone($this->phone);
            $phone->code = '';
            $phone->dt = null;
            $phone->disableValidation();
            $this->object->save($phone);

            $this->object->setCodeAndDtById( $phone->getId(),$this->code, $this->date);

            $this->object->clearRegister();

            $test_phone = $this->object->getOneByPhone($this->phone);
            $this->assertTrue(is_object($test_phone));
            $this->assertEquals($this->code, $test_phone->code);
            $this->assertEquals($this->date, $test_phone->dt);
        }

        /**
         * @covers AccountPhoneManager::getOneByAccountIdAndPhoneAndCode
         */
        function testGetOneByAccountIdAndPhoneAndCode()
        {
            $phones = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($phones as $phone) {
                //Test::dump($phone);
                $account_phone = $this->object->getOneByAccountIdAndPhoneAndCode($phone->account_id, $phone->phone, $phone->code);
                $this->assertTrue(is_object($account_phone));
                $this->assertEquals($phone->account_id, $account_phone->account_id);
                $this->assertEquals($phone->phone, $account_phone->phone);
                $this->assertEquals($phone->code, $account_phone->code);
            }
        }

        /**
         * @covers AccountPhoneManager::setIsConfirmedById
         */
        function testSetIsConfirmedById()
        {
            $phone = $this->object->getOneByPhone($this->phone);
            $phone->is_confirmed = 0;
            $phone->disableValidation();
            $this->object->save($phone);

            $this->object->setIsConfirmedById( $phone->getId());

            $this->object->clearRegister();

            $test_phone = $this->object->getOneByPhone($this->phone);
            $this->assertTrue(is_object($test_phone));
            $this->assertEquals(1, $test_phone->is_confirmed);
        }

        /**
         * @covers AccountPhoneManager::getConfirmedOneByAccountId
         */
        function testGetConfirmedOneByAccountId()
        {
            $phones = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($phones as $phone) {
                $test_object = $this->object->getConfirmedOneByAccountId($phone->account_id);
                $this->assertTrue(is_object($test_object));
                $this->assertTrue((bool)$test_object->is_confirmed);
                $this->assertEquals($test_object->account_id, $phone->account_id);
            }
        }

        /**
         * @covers AccountPhoneManager::getConfirmedOneByAccountId
         */
        function testFailGetConfirmedOneByAccountId()
        {
            $test_object = $this->object->getConfirmedOneByAccountId(null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }


        /**
         * @covers AccountPhoneManager::getOneByPhone
         */
        function testGetOneByPhone()
        {

            $account_phones = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($account_phones as $account_phone) {
                $test_object = $this->object->getOneByPhone($account_phone->phone);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($account_phone->getId(), $test_object->getId());
            }
        }

        /**
         * @covers AccountPhoneManager::getOneByPhone
         */
        function testFailGetOneByPhone()
        {
            $phone = $this->object->getOneByPhone($this->fail_phone);
            $this->assertFalse(is_object($phone));
            $this->assertNull($phone);
        }

        /**
         * @covers AccountPhoneManager::deleteByPhone
         */
        function testDeleteByPhone()
        {
            $this->object->deleteByPhone($this->phone);
            $phones = $this->object->getOneByPhone($this->phone);
            $this->assertNull($phones);
        }





    }
