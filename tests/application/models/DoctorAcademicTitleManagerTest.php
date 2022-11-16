<?php
	class DoctorAcademicTitleManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var DoctorAcademicTitleManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new DoctorAcademicTitleManager();
		}

		/**
		 * @covers DoctorAcademicTitleManager::getListByDoctorId
		 */
		function testGetListByDoctorId(){

			$doctors = ModelManagerFactory::getByName('doctor')->getListWithLimit(10);

			foreach($doctors as $doctor){
				$doctor_academic_titles = $this->object->getListByDoctorId($doctor->getId());
				$this->assertTrue(is_array($doctor_academic_titles));

				if ($doctor_academic_titles)
					foreach($doctor_academic_titles as $doctor_academic_title){
						$this->assertTrue(is_object($doctor_academic_title));
						$this->assertEquals($doctor_academic_title->doctor_id, $doctor->getId());
					}
			}
		}

	}
