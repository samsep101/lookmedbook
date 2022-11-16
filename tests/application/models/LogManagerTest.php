<?php
	class LogManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var LogManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new LogManager();
		}

		/**
		 * @covers LogManager::getListByAccountId
		 */
		function testGetListByAccountId(){

			$accounts = ModelManagerFactory::getByName('account')->getListWithLimit(10);

			foreach($accounts as $account){
				$logs = $this->object->getListByAccountId($account->getId());
				$this->assertTrue(is_array($logs));

				if ($logs)
					foreach($logs as $log){
						$this->assertTrue(is_object($log));
						$this->assertEquals($log->account_id, $account->getId());
					}
			}
		}

	}
