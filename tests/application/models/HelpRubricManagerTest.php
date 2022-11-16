<?php
    class HelpRubricManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var HelpRubricManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new HelpRubricManager();
        }

        /**
         * @covers HelpRubricManager::getActiveList
         */
        function testGetActiveList()
        {
            $test_objects = $this->object->getActiveList();

            foreach ($test_objects as $test_object)
            {
                $this->assertTrue(is_object($test_object));
                $this->assertEquals(1, $test_object->is_active);
            }
        }


    }
