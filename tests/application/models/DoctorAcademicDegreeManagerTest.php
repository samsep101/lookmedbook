<?php
	class DoctorAcademicDegreeManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var DoctorAcademicDegreeManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new DoctorAcademicDegreeManager();
		}

		/**
		 * @covers DoctorAcademicDegreeManager::getListByDoctorId
		 */
		function testGetListByDoctorId(){

			$doctors = ModelManagerFactory::getByName('doctor')->getListWithLimit(10);

			foreach($doctors as $doctor){
				$doctor_academic_degrees = $this->object->getListByDoctorId($doctor->getId());
				$this->assertTrue(is_array($doctor_academic_degrees));

				if ($doctor_academic_degrees)
					foreach($doctor_academic_degrees as $doctor_academic_degree){
						$this->assertTrue(is_object($doctor_academic_degree));
						$this->assertEquals($doctor_academic_degree->doctor_id, $doctor->getId());
					}
			}
		}

	}
