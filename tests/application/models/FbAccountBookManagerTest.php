<?php
	class FbAccountBookManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var FbAccountBookManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new FbAccountBookManager();
		}

		/**
		 * @covers FbAccountBookManager::getListByFbAccountId
		 */
		function testGetListByFbAccountId(){

			$fb_accounts = ModelManagerFactory::getByName('fb_account')->getListWithLimit(10);

			foreach($fb_accounts as $fb_account){
				$fb_account_books = $this->object->getListByFbAccountId($fb_account->getId());
				$this->assertTrue(is_array($fb_account_books));

				if ($fb_account_books)
					foreach($fb_account_books as $fb_account_book){
						$this->assertTrue(is_object($fb_account_book));
						$this->assertEquals($fb_account_book->fb_account_id, $fb_account->getId());
					}
			}
		}

	}
