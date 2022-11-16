<?php
	class MetroManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var MetroManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new MetroManager();
		}

		/**
		 * @covers MetroManager::getListByCityId
		 */
		function testGetListByCityId(){

			$cities = ModelManagerFactory::getByName('city')->getListWithLimit(10);

			foreach($cities as $city){
				$metros = $this->object->getListByCityId($city->getId());
				$this->assertTrue(is_array($metros));

				if ($metros)
					foreach($metros as $metro){
						$this->assertTrue(is_object($metro));
						$this->assertEquals($metro->city_id, $city->getId());
					}
			}
		}

	}
