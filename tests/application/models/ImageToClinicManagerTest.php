<?php
	class ImageToClinicManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var ImageToClinicManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new ImageToClinicManager();
		}

		/**
		 * @covers ImageToClinicManager::getListByImageId
		 */
		function testGetListByImageId(){

			$images = ModelManagerFactory::getByName('image')->getListWithLimit(10);

			foreach($images as $image){
				$image_to_clinics = $this->object->getListByImageId($image->getId());
				$this->assertTrue(is_array($image_to_clinics));

				if ($image_to_clinics)
					foreach($image_to_clinics as $image_to_clinic){
						$this->assertTrue(is_object($image_to_clinic));
						$this->assertEquals($image_to_clinic->image_id, $image->getId());
					}
			}
		}

		/**
		 * @covers ImageToClinicManager::getListByClinicId
		 */
		function testGetListByClinicId(){

			$clinics = ModelManagerFactory::getByName('clinic')->getListWithLimit(10);

			foreach($clinics as $clinic){
				$image_to_clinics = $this->object->getListByClinicId($clinic->getId());
				$this->assertTrue(is_array($image_to_clinics));

				if ($image_to_clinics)
					foreach($image_to_clinics as $image_to_clinic){
						$this->assertTrue(is_object($image_to_clinic));
						$this->assertEquals($image_to_clinic->clinic_id, $clinic->getId());
					}
			}
		}

	}
