<?php
	class FbAccountTelevisionManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var FbAccountTelevisionManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new FbAccountTelevisionManager();
		}

		/**
		 * @covers FbAccountTelevisionManager::getListByFbAccountId
		 */
		function testGetListByFbAccountId(){

			$fb_accounts = ModelManagerFactory::getByName('fb_account')->getListWithLimit(10);

			foreach($fb_accounts as $fb_account){
				$fb_account_televisions = $this->object->getListByFbAccountId($fb_account->getId());
				$this->assertTrue(is_array($fb_account_televisions));

				if ($fb_account_televisions)
					foreach($fb_account_televisions as $fb_account_television){
						$this->assertTrue(is_object($fb_account_television));
						$this->assertEquals($fb_account_television->fb_account_id, $fb_account->getId());
					}
			}
		}

	}
