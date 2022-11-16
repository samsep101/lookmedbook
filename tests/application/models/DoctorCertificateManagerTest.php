<?php
	class DoctorCertificateManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var DoctorCertificateManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new DoctorCertificateManager();
		}

		/**
		 * @covers DoctorCertificateManager::getListByDoctorId
		 */
		function testGetListByDoctorId(){

			$doctors = ModelManagerFactory::getByName('doctor')->getListWithLimit(10);

			foreach($doctors as $doctor){
				$doctor_certificates = $this->object->getListByDoctorId($doctor->getId());
				$this->assertTrue(is_array($doctor_certificates));

				if ($doctor_certificates)
					foreach($doctor_certificates as $doctor_certificate){
						$this->assertTrue(is_object($doctor_certificate));
						$this->assertEquals($doctor_certificate->doctor_id, $doctor->getId());
					}
			}
		}

		/**
		 * @covers DoctorCertificateManager::getListBySpecialtyId
		 */
		function testGetListBySpecialtyId(){

			$specialties = ModelManagerFactory::getByName('specialty')->getListWithLimit(10);

			foreach($specialties as $specialty){
				$doctor_certificates = $this->object->getListBySpecialtyId($specialty->getId());
				$this->assertTrue(is_array($doctor_certificates));

				if ($doctor_certificates)
					foreach($doctor_certificates as $doctor_certificate){
						$this->assertTrue(is_object($doctor_certificate));
						$this->assertEquals($doctor_certificate->specialty_id, $specialty->getId());
					}
			}
		}

	}
