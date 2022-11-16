<?php
	class ClinicLicenseManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var ClinicLicenseManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new ClinicLicenseManager();
		}

		/** 
 		 *
		 * @covers ClinicLicenseManager::getListByClinicId
		 */
		function testGetListByClinicId(){

			$clinics = ModelManagerFactory::getByName('clinic')->getListWithLimit(10);

			foreach($clinics as $clinic){
				$clinic_licenses = $this->object->getListByClinicId($clinic->getId());
				$this->assertTrue(is_array($clinic_licenses));

				if ($clinic_licenses)
					foreach($clinic_licenses as $clinic_license){
						$this->assertTrue(is_object($clinic_license));
						$this->assertEquals($clinic_license->clinic_id, $clinic->getId());
					}
			}
		}

	}
