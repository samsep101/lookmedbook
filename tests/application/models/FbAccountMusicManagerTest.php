<?php
	class FbAccountMusicManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var FbAccountMusicManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new FbAccountMusicManager();
		}

		/**
		 * @covers FbAccountMusicManager::getListByFbAccountId
		 */
		function testGetListByFbAccountId(){

			$fb_accounts = ModelManagerFactory::getByName('fb_account')->getListWithLimit(10);

			foreach($fb_accounts as $fb_account){
				$fb_account_musics = $this->object->getListByFbAccountId($fb_account->getId());
				$this->assertTrue(is_array($fb_account_musics));

				if ($fb_account_musics)
					foreach($fb_account_musics as $fb_account_music){
						$this->assertTrue(is_object($fb_account_music));
						$this->assertEquals($fb_account_music->fb_account_id, $fb_account->getId());
					}
			}
		}

	}
