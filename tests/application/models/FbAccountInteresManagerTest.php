<?php
	class FbAccountInteresManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var FbAccountInteresManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new FbAccountInteresManager();
		}

		/**
		 * @covers FbAccountInteresManager::getListByFbAccountId
		 */
		function testGetListByFbAccountId(){

			$fb_accounts = ModelManagerFactory::getByName('fb_account')->getListWithLimit(10);

			foreach($fb_accounts as $fb_account){
				$fb_account_intereses = $this->object->getListByFbAccountId($fb_account->getId());
				$this->assertTrue(is_array($fb_account_intereses));

				if ($fb_account_intereses)
					foreach($fb_account_intereses as $fb_account_interes){
						$this->assertTrue(is_object($fb_account_interes));
						$this->assertEquals($fb_account_interes->fb_account_id, $fb_account->getId());
					}
			}
		}

	}
