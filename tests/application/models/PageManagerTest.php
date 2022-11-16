<?php
    class PageManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var PageManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new PageManager();
        }

        /**
         * @covers PageManager::getListBySort
         */
        function testGetListBySort()
        {
            $pages = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($pages as $page)
            {
                $test_objects = $this->object->getListBySort($pages->sort);
                $this->assertTrue(is_array($test_objects));

                if ($test_objects)
                    foreach ($test_objects as $test_object)
                    {
                        $this->assertTrue(is_object($test_object));
                        $this->assertEquals($page->sort, $test_object->sort);
                    }
            }
        }

    }
