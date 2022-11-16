<?php
	class FbAccountLikeManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var FbAccountLikeManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new FbAccountLikeManager();
		}

		/**
		 * @covers FbAccountLikeManager::getListByFbAccountId
		 */
		function testGetListByFbAccountId(){

			$fb_accounts = ModelManagerFactory::getByName('fb_account')->getListWithLimit(10);

			foreach($fb_accounts as $fb_account){
				$fb_account_likes = $this->object->getListByFbAccountId($fb_account->getId());
				$this->assertTrue(is_array($fb_account_likes));

				if ($fb_account_likes)
					foreach($fb_account_likes as $fb_account_like){
						$this->assertTrue(is_object($fb_account_like));
						$this->assertEquals($fb_account_like->fb_account_id, $fb_account->getId());
					}
			}
		}

	}
