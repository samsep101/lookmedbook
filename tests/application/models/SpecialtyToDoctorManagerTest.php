<?php
	class SpecialtyToDoctorManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var SpecialtyToDoctorManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new SpecialtyToDoctorManager();
		}

		/**
		 * @covers SpecialtyToDoctorManager::getListBySpecialtyId
		 */
		function testGetListBySpecialtyId(){

			$specialties = ModelManagerFactory::getByName('specialty')->getListWithLimit(10);

			foreach($specialties as $specialty){
				$specialty_to_doctors = $this->object->getListBySpecialtyId($specialty->getId());
				$this->assertTrue(is_array($specialty_to_doctors));

				if ($specialty_to_doctors)
					foreach($specialty_to_doctors as $specialty_to_doctor){
						$this->assertTrue(is_object($specialty_to_doctor));
						$this->assertEquals($specialty_to_doctor->specialty_id, $specialty->getId());
					}
			}
		}

		/**
		 * @covers SpecialtyToDoctorManager::getListByDoctorId
		 */
		function testGetListByDoctorId(){

			$doctors = ModelManagerFactory::getByName('doctor')->getListWithLimit(10);

			foreach($doctors as $doctor){
				$specialty_to_doctors = $this->object->getListByDoctorId($doctor->getId());
				$this->assertTrue(is_array($specialty_to_doctors));

				if ($specialty_to_doctors)
					foreach($specialty_to_doctors as $specialty_to_doctor){
						$this->assertTrue(is_object($specialty_to_doctor));
						$this->assertEquals($specialty_to_doctor->doctor_id, $doctor->getId());
					}
			}
		}

		/**
		 * @covers SpecialtyToDoctorManager::getListByDoctorCertificateId
		 */
		function testGetListByDoctorCertificateId(){

			$doctor_certificates = ModelManagerFactory::getByName('doctor_certificate')->getListWithLimit(10);

			foreach($doctor_certificates as $doctor_certificate){
				$specialty_to_doctors = $this->object->getListByDoctorCertificateId($doctor_certificate->getId());
				$this->assertTrue(is_array($specialty_to_doctors));

				if ($specialty_to_doctors)
					foreach($specialty_to_doctors as $specialty_to_doctor){
						$this->assertTrue(is_object($specialty_to_doctor));
						$this->assertEquals($specialty_to_doctor->doctor_certificate_id, $doctor_certificate->getId());
					}
			}
		}

		/**
		 * @covers SpecialtyToDoctorManager::getListByQualifyingCategoryId
		 */
		function testGetListByQualifyingCategoryId(){

			$qualifying_categories = ModelManagerFactory::getByName('qualifying_category')->getListWithLimit(10);

			foreach($qualifying_categories as $qualifying_category){
				$specialty_to_doctors = $this->object->getListByQualifyingCategoryId($qualifying_category->getId());
				$this->assertTrue(is_array($specialty_to_doctors));

				if ($specialty_to_doctors)
					foreach($specialty_to_doctors as $specialty_to_doctor){
						$this->assertTrue(is_object($specialty_to_doctor));
						$this->assertEquals($specialty_to_doctor->qualifying_category_id, $qualifying_category->getId());
					}
			}
		}
	}
