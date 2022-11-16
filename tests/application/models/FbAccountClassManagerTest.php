<?php
	class FbAccountClassManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var FbAccountClassManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new FbAccountClassManager();
		}

		/**
		 * @covers FbAccountClassManager::getListByFbAccountEducationId
		 */
		function testGetListByFbAccountEducationId(){

			$fb_account_educations = ModelManagerFactory::getByName('fb_account_education')->getListWithLimit(10);

			foreach($fb_account_educations as $fb_account_education){
				$fb_account_classes = $this->object->getListByFbAccountEducationId($fb_account_education->getId());
				$this->assertTrue(is_array($fb_account_classes));

				if ($fb_account_classes)
					foreach($fb_account_classes as $fb_account_class){
						$this->assertTrue(is_object($fb_account_class));
						$this->assertEquals($fb_account_class->fb_account_education_id, $fb_account_education->getId());
					}
			}
		}

	}
