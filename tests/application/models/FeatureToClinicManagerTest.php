<?php
	class FeatureToClinicManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var FeatureToClinicManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new FeatureToClinicManager();
		}

		/**
		 * @covers FeatureToClinicManager::getListByClinicId
		 */
		function testGetListByClinicId(){

			$clinics = ModelManagerFactory::getByName('clinic')->getListWithLimit(10);

			foreach($clinics as $clinic){
				$feature_to_clinics = $this->object->getListByClinicId($clinic->getId());
				$this->assertTrue(is_array($feature_to_clinics));

				if ($feature_to_clinics)
					foreach($feature_to_clinics as $feature_to_clinic){
						$this->assertTrue(is_object($feature_to_clinic));
						$this->assertEquals($feature_to_clinic->clinic_id, $clinic->getId());
					}
			}
		}

		/**
		 * @covers FeatureToClinicManager::getListByFeatureId
		 */
		function testGetListByFeatureId(){

			$features = ModelManagerFactory::getByName('feature')->getListWithLimit(10);

			foreach($features as $feature){
				$feature_to_clinics = $this->object->getListByFeatureId($feature->getId());
				$this->assertTrue(is_array($feature_to_clinics));

				if ($feature_to_clinics)
					foreach($feature_to_clinics as $feature_to_clinic){
						$this->assertTrue(is_object($feature_to_clinic));
						$this->assertEquals($feature_to_clinic->feature_id, $feature->getId());
					}
			}
		}

	}
