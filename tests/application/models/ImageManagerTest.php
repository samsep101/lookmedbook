<?php
	class ImageManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var ImageManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new ImageManager();
		}

		/** 
 		 * todo: проверить тест 
		 * @covers ImageManager::getListByDoctorId
		 */
		function testGetListByDoctorId(){

			$doctors = ModelManagerFactory::getByName('doctor')->getListWithLimit(10);

			foreach($doctors as $doctor){
				$images = $this->object->getListByDoctorId($doctor->getId());
				$this->assertTrue(is_array($images));

				if ($images)
					foreach($images as $image){
						$this->assertTrue(is_object($image));
						$this->assertEquals($image->doctor_id, $doctor->getId());
					}
			}
		}

		/** 
 		 * todo: проверить тест 
		 * @covers ImageManager::getListByClinicId
		 */
		function testGetListByClinicId(){

			$clinics = ModelManagerFactory::getByName('clinic')->getListWithLimit(10);

			foreach($clinics as $clinic){
				$images = $this->object->getListByClinicId($clinic->getId());
				$this->assertTrue(is_array($images));

				if ($images)
					foreach($images as $image){
						$this->assertTrue(is_object($image));
						$this->assertEquals($image->clinic_id, $clinic->getId());
					}
			}
		}

	}
