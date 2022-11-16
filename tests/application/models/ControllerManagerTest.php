<?php
    class ControllerManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var ControllerManager
         */
        protected $object;
        protected $fail_code = 'fail';
        protected function setUp()
        {
            $this->object = new ControllerManager();
        }

        /**
         * @covers ControllerManager::getOneByCode
         */
        function testGetOneByCode()
        {

            $controllers = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($controllers as $controller) {
                $test_object = $this->object->getOneByCode($controller->code);
                    $this->assertTrue(is_object($test_object));
                $this->assertEquals($controller->getId(), $test_object->getId());
            }
        }

        /**
         * @covers ControllerManager::getOneByCode
         */
        function testFailGetOneByCode()
        {

            $controllers = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($controllers as $controller) {
                $test_object = $this->object->getOneByCode($this->fail_code);
                $this->assertNull($test_object);
            }
        }

        /**
         *
         * @covers ControllerManager::getActiveList
         */
        function testGetActiveList()
        {
            $controllers = $this->object->getActiveList();
            $this->assertTrue(is_array($controllers));

            if ($controllers)
                foreach ($controllers as $controller) {
                    $this->assertTrue(is_object($controller));
                    $this->assertEquals(1, $controller->is_active);
                }
        }
    }
