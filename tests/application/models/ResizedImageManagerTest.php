<?php
	class ResizedImageManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var ResizedImageManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new ResizedImageManager();
		}

		/**
		 * @covers ResizedImageManager::getOneByImageIdAndWidthAndHeightAndAction
		 */
		function testGetOneByImageIdAndWidthAndHeightAndAction()
        {
            $resized_images = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($resized_images as $resized_image) {
                $test_object = $this->object->getOneByImageIdAndWidthAndHeightAndAction($resized_image->image_id,$resized_image->width,$resized_image->height,$resized_image->action);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($resized_image->getId(), $test_object->getId());
            }
		}

        /**
         * @covers ResizedImageManager::getOneByImageIdAndWidthAndHeightAndAction
         */
        function testFailGetOneByImageIdAndWidthAndHeightAndAction()
        {
            $test_object = $this->object->getOneByImageIdAndWidthAndHeightAndAction(null,null,null,'');
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

	}
