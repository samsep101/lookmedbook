<?php
	class FeatureManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var FeatureManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new FeatureManager();
		}

		/**
		 * @covers FeatureManager::getActiveListByClinicId
		 */
		function testGetActiveListByClinicId(){

			$clinics = ModelManagerFactory::getByName('clinic')->getListWithLimit(10);

			foreach($clinics as $clinic){
				$features = $this->object->getActiveListByClinicId($clinic->getId());
				$this->assertTrue(is_array($features));

				if ($features)
					foreach($features as $feature){
						$this->assertTrue((bool)$feature->active);
						$this->assertTrue(is_object($feature));
						$this->assertEquals($feature->clinic_id, $clinic->getId());
					}
			}
		}

	}
