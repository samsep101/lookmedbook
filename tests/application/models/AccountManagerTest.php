<?php
    class AccountManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var AccountManager
         */
        protected $object;

        protected $email = 'test@gmail.com';
        protected $fail_email = 'fail';
        protected $password = 'test';
        protected $fail_password = 'fail';
        protected $email_confirm_code = 'test';
        protected $fail_confirm_code = 'fail';

        protected $full_name = 'Test Test Test';
        protected $fail_full_name = 'tset';
        protected $nick = 'test';
        protected $fail_nick = 'fail';

        protected function setUp()
        {
            $this->object = new AccountManager();
            $this->deleteAndCreateAccount();
        }

        private function deleteAndCreateAccount()
        {
            $this->object->deleteByEmail($this->email);
            $this->object->deleteByNick($this->nick);

            $account = new AccountModel();
            $account->full_name = $this->full_name;
            $account->email = $this->email;
            $account->nick = $this->nick;
            $account->email_confirm_code = $this->email_confirm_code;
            $account->password_hash = PasswordHashGenerator::generate($this->password);
            $account->disableValidation();

            ModelManagerFactory::getByName('account')->save($account);
            $this->password = PasswordHashGenerator::generate($this->password);
        }

        /**
         * @covers AccountManager::getOneByEmailAndPasswordHash
         */
        function testGetOneByEmailAndPasswordHash()
        {
            $test_object = $this->object->getOneByEmailAndPasswordHash($this->email, $this->password);
            $this->assertTrue(is_object($test_object));
            $this->assertEquals($this->email, $test_object->email);
            $this->assertEquals($this->password, $test_object->password_hash);
        }

        /**
         * @covers AccountManager::getOneByEmailAndPasswordHash
         */
        function testFailGetOneByEmailAndPasswordHash()
        {
            $account = $this->object->getOneByEmailAndPasswordHash($this->fail_email, $this->fail_password);
            $this->assertFalse(is_object($account));
            $this->assertNull($account);
        }

        /**
         * @covers AccountManager::getOneByEmail
         */
        function testGetOneByEmail()
        {
            $test_object = $this->object->getOneByEmail($this->email);
            $this->assertTrue(is_object($test_object));
            $this->assertEquals($this->email, $test_object->email);
        }

        /**
         * @covers AccountManager::getOneByEmail
         */
        function testFailGetOneByEmail()
        {
            $account = $this->object->getOneByEmail($this->fail_email);
            $this->assertFalse(is_object($account));
            $this->assertNull($account);
        }

        /**
         * @covers AccountManager::getOneByNick
         */
        function testGetOneByNick()
        {
            $account = $this->object->getOneByNick($this->nick);
            $this->assertTrue(is_object($account));
            $this->assertEquals($this->nick, $account->nick);
        }

        /**
         * @covers AccountManager::getOneByNick
         */
        function testFailGetOneByNick()
        {
            $account = $this->object->getOneByNick($this->fail_nick);
            $this->assertFalse(is_object($account));
            $this->assertNull($account);
        }

        /**
         * @covers AccountManager::getOneByEmailAndEmailConfirmCode
         */
        function testGetOneByEmailAndEmailConfirmCode()
        {
            $test_object = $this->object->getOneByEmailAndEmailConfirmCode($this->email, $this->email_confirm_code);
            $this->assertTrue(is_object($test_object));
            $this->assertEquals($this->email, $test_object->email);
            $this->assertEquals($this->email_confirm_code, $test_object->email_confirm_code);

        }

        /**
         * @covers AccountManager::getOneByEmailAndEmailConfirmCode
         */
        function testFailGetOneByEmailAndEmailConfirmCode()
        {
            $account = $this->object->getOneByEmailAndEmailConfirmCode($this->fail_email, $this->fail_confirm_code);
            $this->assertFalse(is_object($account));
            $this->assertNull($account);
        }

        /**
         * @covers AccountManager::confirmEmail
         */
        function testConfirmEmail()
        {
            $account = $this->object->getOneByEmail($this->email);
            $account->is_confirm_email = 0;
            $account->disableValidation();
            $this->object->save($account);

            $this->object->confirmEmail($account->getId());

            $this->object->clearRegister();
            $test_account = $this->object->getOneByEmail($this->email);
            $this->assertTrue(is_object($test_account));
            $this->assertEquals(1, $test_account->is_confirm_email);
        }


        /**
         * @covers AccountManager::deleteByEmail
         */
        function testDeleteByEmail()
        {
            $this->object->deleteByEmail($this->email);
            $account = $this->object->getOneByEmail($this->email);
            $this->assertNull($account);
        }


        /**
         * @covers AccountManager::deleteByNick
         */
        function testDeleteByNick()
        {
            $this->object->deleteByNick($this->nick);
            $account = $this->object->getOneByNick($this->nick);
            $this->assertNull($account);
        }


        /**
         * @covers AccountManager::setNewPasswordHashByEmail
         */
        function testSetNewPasswordHashByEmail()
        {
            $account = $this->object->getOneByEmail($this->email);
            $account->password_hash = '';
            $account->disableValidation();
            $this->object->save($account);

            $this->object->setNewPasswordHashByEmail(PasswordHashGenerator::generate($this->password), $this->email);

            $this->object->clearRegister();
            $test_account = $this->object->getOneByEmail($this->email);
            $this->assertTrue(is_object($test_account));
            $this->assertEquals(PasswordHashGenerator::generate($this->password), $test_account->password_hash);
        }


        /**
         * @covers AccountManager::setEmailAndEmailConfirmCodeAndPasswordHashByAccountId
         */
        function testSetEmailAndEmailConfirmCodeByAccountId()
        {
            $account = $this->object->getOneByEmail($this->email);
            $account->password_hash = '';
            $account->disableValidation();
            $this->object->save($account);

            $this->object->setEmailAndEmailConfirmCodeAndPasswordHashByAccountId($this->email, $this->email_confirm_code, $account->getId(), PasswordHashGenerator::generate($this->password));

            $this->object->clearRegister();
            $test_account = $this->object->getOneByEmail($this->email);
            $this->assertTrue(is_object($test_account));
            $this->assertEquals(PasswordHashGenerator::generate($this->password), $test_account->password_hash);
            $this->assertEquals($this->email, $test_account->email);
            $this->assertEquals($this->email_confirm_code, $test_account->email_confirm_code);
        }

        /**
         * @covers AccountManager::getOneByFullName
         */
        function testGetOneByFullName()
        {
            $account = $this->object->getOneByFullName($this->full_name);
            $this->assertTrue(is_object($account));
            $this->assertEquals($this->full_name, $account->full_name);
        }

        /**
         * @covers AccountManager::getOneByFullName
         */
        function testFailGetOneByFullName()
        {
            $account = $this->object->getOneByFullName($this->fail_full_name);
            $this->assertFalse(is_object($account));
            $this->assertNull($account);
        }

        /**
         * @covers AccountManager::setFullNameByAccountId
         */
        function testSetFullNameByAccountId()
        {
            $account = $this->object->getOneByFullName($this->full_name);
            $account->full_name = '';
            $account->disableValidation();
            $this->object->save($account);

            $this->object->setFullNameByAccountId($account->getId(), $this->full_name);

            $this->object->clearRegister();

            $test_account = $this->object->getOneByFullName($this->full_name);
            $this->assertTrue(is_object($test_account));
            $this->assertEquals($this->full_name, $test_account->full_name);
        }

    }
