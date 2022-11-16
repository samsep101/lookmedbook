<?php
	class FbAccountMovieManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var FbAccountMovieManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new FbAccountMovieManager();
		}

		/**
		 * @covers FbAccountMovieManager::getListByFbAccountId
		 */
		function testGetListByFbAccountId(){

			$fb_accounts = ModelManagerFactory::getByName('fb_account')->getListWithLimit(10);

			foreach($fb_accounts as $fb_account){
				$fb_account_movies = $this->object->getListByFbAccountId($fb_account->getId());
				$this->assertTrue(is_array($fb_account_movies));

				if ($fb_account_movies)
					foreach($fb_account_movies as $fb_account_movie){
						$this->assertTrue(is_object($fb_account_movie));
						$this->assertEquals($fb_account_movie->fb_account_id, $fb_account->getId());
					}
			}
		}

	}
