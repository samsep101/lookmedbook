<?php
	class FbAccountGroupManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var FbAccountGroupManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new FbAccountGroupManager();
		}

		/**
		 * @covers FbAccountGroupManager::getListByFbAccountId
		 */
		function testGetListByFbAccountId(){

			$fb_accounts = ModelManagerFactory::getByName('fb_account')->getListWithLimit(10);

			foreach($fb_accounts as $fb_account){
				$fb_account_groups = $this->object->getListByFbAccountId($fb_account->getId());
				$this->assertTrue(is_array($fb_account_groups));

				if ($fb_account_groups)
					foreach($fb_account_groups as $fb_account_group){
						$this->assertTrue(is_object($fb_account_group));
						$this->assertEquals($fb_account_group->fb_account_id, $fb_account->getId());
					}
			}
		}

	}
