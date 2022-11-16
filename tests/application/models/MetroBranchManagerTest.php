<?php
	class MetroBranchManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var MetroBranchManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new MetroBranchManager();
		}

		/**
		 * @covers MetroBranchManager::getListByMetroId
		 */
		function testGetListByMetroId(){

			$metros = ModelManagerFactory::getByName('metro')->getListWithLimit(10);

			foreach($metros as $metro){
				$metro_branchs = $this->object->getListByMetroId($metro->getId());
				$this->assertTrue(is_array($metro_branchs));

				if ($metro_branchs)
					foreach($metro_branchs as $metro_branch){
						$this->assertTrue(is_object($metro_branch));
						$this->assertEquals($metro_branch->metro_id, $metro->getId());
					}
			}
		}

	}
