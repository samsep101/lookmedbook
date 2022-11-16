<?php
	class PurposeOfVisitToSpecialtyManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var PurposeOfVisitToSpecialtyManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new PurposeOfVisitToSpecialtyManager();
		}

		/**
		 * @covers PurposeOfVisitToSpecialtyManager::getListByPurposeOfVisitId
		 */
		function testGetListByPurposeOfVisitId(){

			$purpose_of_visits = ModelManagerFactory::getByName('purpose_of_visit')->getListWithLimit(10);

			foreach($purpose_of_visits as $purpose_of_visit){
				$purpose_of_visit_to_specialties = $this->object->getListByPurposeOfVisitId($purpose_of_visit->getId());
				$this->assertTrue(is_array($purpose_of_visit_to_specialties));

				if ($purpose_of_visit_to_specialties)
					foreach($purpose_of_visit_to_specialties as $purpose_of_visit_to_specialty){
						$this->assertTrue(is_object($purpose_of_visit_to_specialty));
						$this->assertEquals($purpose_of_visit_to_specialty->purpose_of_visit_id, $purpose_of_visit->getId());
					}
			}
		}

		/**
		 * @covers PurposeOfVisitToSpecialtyManager::getListBySpecialtyId
		 */
		function testGetListBySpecialtyId(){

			$specialties = ModelManagerFactory::getByName('specialty')->getListWithLimit(10);

			foreach($specialties as $specialty){
				$purpose_of_visit_to_specialties = $this->object->getListBySpecialtyId($specialty->getId());
				$this->assertTrue(is_array($purpose_of_visit_to_specialties));

				if ($purpose_of_visit_to_specialties)
					foreach($purpose_of_visit_to_specialties as $purpose_of_visit_to_specialty){
						$this->assertTrue(is_object($purpose_of_visit_to_specialty));
						$this->assertEquals($purpose_of_visit_to_specialty->specialty_id, $specialty->getId());
					}
			}
		}
	}
