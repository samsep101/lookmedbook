<?php
	class MedicineToDiseaseManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var MedicineToDiseaseManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new MedicineToDiseaseManager();
		}

		/**
		 * @covers MedicineToDiseaseManager::getOneByDiseaseId
		 */
		function testGetOneByDiseaseId(){

			$medicine_to_diseases = $this->object->getListWithLimit(20);

			$this->object->clearRegister();

			foreach($medicine_to_diseases as $medicine_to_disease){
				$test_object = $this->object->getOneByDiseaseId($medicine_to_disease->disease_id);
						$this->assertTrue(is_object($test_object));
				$this->assertEquals($medicine_to_disease->getId(), $test_object->getId());
			}
		}

	}
