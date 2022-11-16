<?php
	class MailruAccountManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var MailruAccountManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new MailruAccountManager();
		}

		/**
		 * @covers MailruAccountManager::getOneByUid
		 */
		function testGetOneByUid(){

			$mailru_accounts = $this->object->getListWithLimit(20);

			$this->object->clearRegister();

			foreach($mailru_accounts as $mailru_account){
				$test_object = $this->object->getOneByUid($mailru_account->uid);
						$this->assertTrue(is_object($test_object));
				$this->assertEquals($mailru_account->getId(), $test_object->getId());
			}
		}

        /**
         * @covers MailruAccountManager::getOneByUid
         */
        function testFailGetOneByUid()
        {
            $test_object = $this->object->getOneByUid(null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);

        }

		/**
		 * @covers MailruAccountManager::getOneByAccountId
		 */
		function testGetOneByAccountId(){

			$mailru_accounts = $this->object->getListWithLimit(20);

			$this->object->clearRegister();

			foreach($mailru_accounts as $mailru_account){
				$test_object = $this->object->getOneByAccountId($mailru_account->account_id);
						$this->assertTrue(is_object($test_object));
				$this->assertEquals($mailru_account->getId(), $test_object->getId());
			}
		}

        /**
         * @covers MailruAccountManager::getOneByAccountId
         */
        function testFailGetOneByAccountId()
        {
            $test_object = $this->object->getOneByAccountId(null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }


	}
