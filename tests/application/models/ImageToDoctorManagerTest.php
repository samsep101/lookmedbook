<?php
	class ImageToDoctorManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var ImageToDoctorManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new ImageToDoctorManager();
		}

		/**
		 * @covers ImageToDoctorManager::getListByDoctorId
		 */
		function testGetListByDoctorId(){

			$doctors = ModelManagerFactory::getByName('doctor')->getListWithLimit(10);

			foreach($doctors as $doctor){
				$image_to_doctors = $this->object->getListByDoctorId($doctor->getId());
				$this->assertTrue(is_array($image_to_doctors));

				if ($image_to_doctors)
					foreach($image_to_doctors as $image_to_doctor){
						$this->assertTrue(is_object($image_to_doctor));
						$this->assertEquals($image_to_doctor->doctor_id, $doctor->getId());
					}
			}
		}

		/**
		 * @covers ImageToDoctorManager::getListByImageId
		 */
		function testGetListByImageId(){

			$images = ModelManagerFactory::getByName('image')->getListWithLimit(10);

			foreach($images as $image){
				$image_to_doctors = $this->object->getListByImageId($image->getId());
				$this->assertTrue(is_array($image_to_doctors));

				if ($image_to_doctors)
					foreach($image_to_doctors as $image_to_doctor){
						$this->assertTrue(is_object($image_to_doctor));
						$this->assertEquals($image_to_doctor->image_id, $image->getId());
					}
			}
		}

	}
