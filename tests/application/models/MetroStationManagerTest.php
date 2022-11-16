<?php
	class MetroStationManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var MetroStationManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new MetroStationManager();
		}

		/**
		 * @covers MetroStationManager::getListByMetroBranchId
		 */
		function testGetListByMetroBranchId(){

			$metro_branchs = ModelManagerFactory::getByName('metro_branch')->getListWithLimit(10);

			foreach($metro_branchs as $metro_branch){
				$metro_stations = $this->object->getListByMetroBranchId($metro_branch->getId());
				$this->assertTrue(is_array($metro_stations));

				if ($metro_stations)
					foreach($metro_stations as $metro_station){
						$this->assertTrue(is_object($metro_station));
						$this->assertEquals($metro_station->metro_branch_id, $metro_branch->getId());
					}
			}
		}

	}
