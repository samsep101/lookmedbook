<?php
	class ClinicPhoneManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var ClinicPhoneManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new ClinicPhoneManager();
		}

		/** 
 		 * todo: проверить тест 
		 * @covers ClinicPhoneManager::getListByClinicId
		 */
		function testGetListByClinicId(){

			$clinics = ModelManagerFactory::getByName('clinic')->getListWithLimit(10);

			foreach($clinics as $clinic){
				$clinic_phones = $this->object->getListByClinicId($clinic->getId());
				$this->assertTrue(is_array($clinic_phones));

				if ($clinic_phones)
					foreach($clinic_phones as $clinic_phone){
						$this->assertTrue(is_object($clinic_phone));
						$this->assertEquals($clinic_phone->clinic_id, $clinic->getId());
					}
			}
		}

	}
